"""
Local fine-tuning script for DeepSeek models using Unsloth for efficiency.

This script supports:
- DeepSeek-R1-Distill models (1.5B, 7B, 8B, 14B, 32B, 70B)
- LoRA/QLoRA fine-tuning for memory efficiency
- Custom markdown-derived datasets

Requirements:
    pip install unsloth transformers datasets peft accelerate bitsandbytes
"""

import json
import os
from pathlib import Path
from typing import Optional
from dataclasses import dataclass, field

import torch
from datasets import Dataset, load_dataset
from transformers import TrainingArguments


@dataclass
class FinetuneConfig:
    """Configuration for fine-tuning."""
    # Model settings
    model_name: str = "deepseek-ai/DeepSeek-R1-Distill-Qwen-1.5B"
    max_seq_length: int = 2048
    load_in_4bit: bool = True

    # LoRA settings
    lora_r: int = 16
    lora_alpha: int = 16
    lora_dropout: float = 0.0

    # Training settings
    output_dir: str = "./outputs"
    num_train_epochs: int = 3
    per_device_train_batch_size: int = 2
    gradient_accumulation_steps: int = 4
    learning_rate: float = 2e-4
    warmup_steps: int = 5
    logging_steps: int = 1
    save_steps: int = 50
    max_steps: int = -1  # Set to positive number to limit training

    # Data
    dataset_path: str = "data/training_data.jsonl"


def load_jsonl_dataset(file_path: str) -> Dataset:
    """Load a JSONL dataset with messages format."""
    data = []
    with open(file_path, 'r', encoding='utf-8') as f:
        for line in f:
            data.append(json.loads(line))

    return Dataset.from_list(data)


def format_conversation_for_deepseek(example: dict) -> dict:
    """
    Format a conversation for DeepSeek training.

    DeepSeek uses the ChatML format:
    <|begin▁of▁sentence|><|User|>question<|Assistant|>answer<|end▁of▁sentence|>
    """
    messages = example.get("messages", [])

    formatted_text = ""
    for msg in messages:
        role = msg["role"]
        content = msg["content"]

        if role == "system":
            formatted_text += f"<|System|>{content}\n"
        elif role == "user":
            formatted_text += f"<|User|>{content}\n"
        elif role == "assistant":
            formatted_text += f"<|Assistant|>{content}\n"

    return {"text": formatted_text}


def train_with_unsloth(config: FinetuneConfig):
    """
    Train using Unsloth for 2-5x faster training with less memory.
    """
    try:
        from unsloth import FastLanguageModel
    except ImportError:
        print("Unsloth not installed. Install with: pip install unsloth")
        print("Falling back to standard training...")
        return train_standard(config)

    print(f"Loading model: {config.model_name}")

    # Load model with Unsloth optimizations
    model, tokenizer = FastLanguageModel.from_pretrained(
        model_name=config.model_name,
        max_seq_length=config.max_seq_length,
        dtype=None,  # Auto-detect
        load_in_4bit=config.load_in_4bit,
    )

    # Add LoRA adapters
    model = FastLanguageModel.get_peft_model(
        model,
        r=config.lora_r,
        target_modules=[
            "q_proj", "k_proj", "v_proj", "o_proj",
            "gate_proj", "up_proj", "down_proj",
        ],
        lora_alpha=config.lora_alpha,
        lora_dropout=config.lora_dropout,
        bias="none",
        use_gradient_checkpointing="unsloth",
        random_state=42,
    )

    # Load dataset
    print(f"Loading dataset: {config.dataset_path}")
    dataset = load_jsonl_dataset(config.dataset_path)
    print(f"Loaded {len(dataset)} examples")

    # Format for training
    dataset = dataset.map(format_conversation_for_deepseek)

    # Training arguments
    from trl import SFTTrainer

    trainer = SFTTrainer(
        model=model,
        tokenizer=tokenizer,
        train_dataset=dataset,
        dataset_text_field="text",
        max_seq_length=config.max_seq_length,
        dataset_num_proc=2,
        packing=False,
        args=TrainingArguments(
            per_device_train_batch_size=config.per_device_train_batch_size,
            gradient_accumulation_steps=config.gradient_accumulation_steps,
            warmup_steps=config.warmup_steps,
            max_steps=config.max_steps if config.max_steps > 0 else None,
            num_train_epochs=config.num_train_epochs if config.max_steps <= 0 else None,
            learning_rate=config.learning_rate,
            fp16=not torch.cuda.is_bf16_supported(),
            bf16=torch.cuda.is_bf16_supported(),
            logging_steps=config.logging_steps,
            optim="adamw_8bit",
            weight_decay=0.01,
            lr_scheduler_type="linear",
            seed=42,
            output_dir=config.output_dir,
            save_steps=config.save_steps,
        ),
    )

    print("Starting training...")
    trainer.train()

    # Save the model
    print(f"Saving model to {config.output_dir}")
    model.save_pretrained(config.output_dir)
    tokenizer.save_pretrained(config.output_dir)

    # Also save merged model for easier inference
    merged_dir = f"{config.output_dir}_merged"
    print(f"Saving merged model to {merged_dir}")
    model.save_pretrained_merged(merged_dir, tokenizer, save_method="merged_16bit")

    print("Training complete!")
    return trainer


def train_standard(config: FinetuneConfig):
    """
    Standard training with transformers + peft.
    Works on macOS (MPS) and Linux (CUDA).
    """
    from transformers import AutoModelForCausalLM, AutoTokenizer
    from peft import LoraConfig, get_peft_model
    from trl import SFTTrainer

    print(f"Loading model: {config.model_name}")

    # Detect device
    if torch.cuda.is_available():
        device_map = "auto"
        dtype = torch.float16
        print("Using CUDA")
    elif torch.backends.mps.is_available():
        device_map = {"": "mps"}
        dtype = torch.float32  # MPS works better with float32
        print("Using MPS (Apple Silicon)")
    else:
        device_map = {"": "cpu"}
        dtype = torch.float32
        print("Using CPU (this will be slow)")

    # Try to use bitsandbytes 4-bit if available (Linux/CUDA only)
    quantization_config = None
    if config.load_in_4bit and torch.cuda.is_available():
        try:
            from transformers import BitsAndBytesConfig
            quantization_config = BitsAndBytesConfig(
                load_in_4bit=True,
                bnb_4bit_quant_type="nf4",
                bnb_4bit_compute_dtype=torch.float16,
                bnb_4bit_use_double_quant=True,
            )
            print("Using 4-bit quantization")
        except ImportError:
            print("bitsandbytes not available, using full precision")

    # Load model
    model = AutoModelForCausalLM.from_pretrained(
        config.model_name,
        quantization_config=quantization_config,
        torch_dtype=dtype,
        device_map=device_map,
        trust_remote_code=True,
    )

    tokenizer = AutoTokenizer.from_pretrained(
        config.model_name,
        trust_remote_code=True,
    )
    tokenizer.pad_token = tokenizer.eos_token
    tokenizer.padding_side = "right"

    # Prepare for k-bit training if using quantization
    if quantization_config is not None:
        from peft import prepare_model_for_kbit_training
        model = prepare_model_for_kbit_training(model)

    # LoRA config
    lora_config = LoraConfig(
        r=config.lora_r,
        lora_alpha=config.lora_alpha,
        target_modules=[
            "q_proj", "k_proj", "v_proj", "o_proj",
            "gate_proj", "up_proj", "down_proj",
        ],
        lora_dropout=config.lora_dropout,
        bias="none",
        task_type="CAUSAL_LM",
    )

    model = get_peft_model(model, lora_config)
    model.print_trainable_parameters()

    # Load dataset
    print(f"Loading dataset: {config.dataset_path}")
    dataset = load_jsonl_dataset(config.dataset_path)
    print(f"Loaded {len(dataset)} examples")

    # Format for training
    dataset = dataset.map(format_conversation_for_deepseek)

    # Determine precision settings
    use_fp16 = torch.cuda.is_available()
    use_bf16 = False

    # Train
    trainer = SFTTrainer(
        model=model,
        tokenizer=tokenizer,
        train_dataset=dataset,
        dataset_text_field="text",
        max_seq_length=config.max_seq_length,
        args=TrainingArguments(
            per_device_train_batch_size=config.per_device_train_batch_size,
            gradient_accumulation_steps=config.gradient_accumulation_steps,
            warmup_steps=config.warmup_steps,
            max_steps=config.max_steps if config.max_steps > 0 else None,
            num_train_epochs=config.num_train_epochs if config.max_steps <= 0 else None,
            learning_rate=config.learning_rate,
            fp16=use_fp16,
            bf16=use_bf16,
            logging_steps=config.logging_steps,
            optim="adamw_torch",  # Works on all platforms
            weight_decay=0.01,
            lr_scheduler_type="linear",
            seed=42,
            output_dir=config.output_dir,
            save_steps=config.save_steps,
        ),
    )

    print("Starting training...")
    trainer.train()

    # Save
    print(f"Saving model to {config.output_dir}")
    trainer.save_model(config.output_dir)
    tokenizer.save_pretrained(config.output_dir)

    print("Training complete!")
    return trainer


def main():
    import argparse

    parser = argparse.ArgumentParser(description="Fine-tune DeepSeek locally")
    parser.add_argument(
        "--model",
        type=str,
        default="deepseek-ai/DeepSeek-R1-Distill-Qwen-1.5B",
        help="Model name (use smaller models for testing)"
    )
    parser.add_argument("--dataset", type=str, required=True, help="Path to JSONL dataset")
    parser.add_argument("--output-dir", type=str, default="./outputs", help="Output directory")
    parser.add_argument("--epochs", type=int, default=3, help="Number of epochs")
    parser.add_argument("--max-steps", type=int, default=-1, help="Max training steps (-1 for full epochs)")
    parser.add_argument("--batch-size", type=int, default=2, help="Batch size per device")
    parser.add_argument("--lr", type=float, default=2e-4, help="Learning rate")
    parser.add_argument("--lora-r", type=int, default=16, help="LoRA rank")
    parser.add_argument("--max-seq-length", type=int, default=2048, help="Max sequence length")
    parser.add_argument("--no-4bit", action="store_true", help="Disable 4-bit quantization")
    parser.add_argument("--use-standard", action="store_true", help="Use standard training (no Unsloth)")

    args = parser.parse_args()

    config = FinetuneConfig(
        model_name=args.model,
        dataset_path=args.dataset,
        output_dir=args.output_dir,
        num_train_epochs=args.epochs,
        max_steps=args.max_steps,
        per_device_train_batch_size=args.batch_size,
        learning_rate=args.lr,
        lora_r=args.lora_r,
        max_seq_length=args.max_seq_length,
        load_in_4bit=not args.no_4bit,
    )

    if args.use_standard:
        train_standard(config)
    else:
        train_with_unsloth(config)


if __name__ == "__main__":
    main()
