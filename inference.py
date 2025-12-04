"""
Inference script for the fine-tuned DeepSeek model.
"""

import torch
from transformers import AutoModelForCausalLM, AutoTokenizer
from peft import PeftModel
from typing import Optional


def load_model(
    base_model: str = "deepseek-ai/DeepSeek-R1-Distill-Qwen-1.5B",
    lora_path: Optional[str] = None,
    merged_path: Optional[str] = None,
    device: str = "auto",
    load_in_4bit: bool = True,
):
    """
    Load the fine-tuned model for inference.

    Args:
        base_model: Base model name
        lora_path: Path to LoRA adapter (if using adapters)
        merged_path: Path to merged model (if pre-merged)
        device: Device to load on
        load_in_4bit: Whether to quantize
    """
    if merged_path:
        # Load pre-merged model
        print(f"Loading merged model from {merged_path}")
        model = AutoModelForCausalLM.from_pretrained(
            merged_path,
            torch_dtype=torch.float16,
            device_map=device,
            trust_remote_code=True,
        )
        tokenizer = AutoTokenizer.from_pretrained(merged_path, trust_remote_code=True)
    elif lora_path:
        # Load base + LoRA adapter
        print(f"Loading base model: {base_model}")
        from transformers import BitsAndBytesConfig

        if load_in_4bit:
            bnb_config = BitsAndBytesConfig(
                load_in_4bit=True,
                bnb_4bit_quant_type="nf4",
                bnb_4bit_compute_dtype=torch.float16,
            )
            model = AutoModelForCausalLM.from_pretrained(
                base_model,
                quantization_config=bnb_config,
                device_map=device,
                trust_remote_code=True,
            )
        else:
            model = AutoModelForCausalLM.from_pretrained(
                base_model,
                torch_dtype=torch.float16,
                device_map=device,
                trust_remote_code=True,
            )

        print(f"Loading LoRA adapter from {lora_path}")
        model = PeftModel.from_pretrained(model, lora_path)
        tokenizer = AutoTokenizer.from_pretrained(base_model, trust_remote_code=True)
    else:
        # Just load base model
        print(f"Loading base model: {base_model}")
        model = AutoModelForCausalLM.from_pretrained(
            base_model,
            torch_dtype=torch.float16,
            device_map=device,
            trust_remote_code=True,
        )
        tokenizer = AutoTokenizer.from_pretrained(base_model, trust_remote_code=True)

    return model, tokenizer


def generate_response(
    model,
    tokenizer,
    prompt: str,
    system_prompt: Optional[str] = None,
    max_new_tokens: int = 512,
    temperature: float = 0.7,
    top_p: float = 0.9,
) -> str:
    """
    Generate a response from the model.
    """
    # Format prompt in DeepSeek's ChatML style
    if system_prompt:
        formatted = f"<|System|>{system_prompt}\n<|User|>{prompt}\n<|Assistant|>"
    else:
        formatted = f"<|User|>{prompt}\n<|Assistant|>"

    inputs = tokenizer(formatted, return_tensors="pt").to(model.device)

    with torch.no_grad():
        outputs = model.generate(
            **inputs,
            max_new_tokens=max_new_tokens,
            temperature=temperature,
            top_p=top_p,
            do_sample=True,
            pad_token_id=tokenizer.eos_token_id,
        )

    response = tokenizer.decode(outputs[0], skip_special_tokens=True)

    # Extract just the assistant response
    if "<|Assistant|>" in response:
        response = response.split("<|Assistant|>")[-1].strip()

    return response


def interactive_chat(model, tokenizer, system_prompt: Optional[str] = None):
    """
    Run an interactive chat session.
    """
    print("\n" + "=" * 50)
    print("Interactive Chat with Fine-tuned DeepSeek")
    print("Type 'quit' or 'exit' to end the session")
    print("=" * 50 + "\n")

    while True:
        try:
            user_input = input("You: ").strip()
            if user_input.lower() in ['quit', 'exit', 'q']:
                print("Goodbye!")
                break

            if not user_input:
                continue

            response = generate_response(
                model,
                tokenizer,
                user_input,
                system_prompt=system_prompt,
            )
            print(f"\nAssistant: {response}\n")

        except KeyboardInterrupt:
            print("\nGoodbye!")
            break


def main():
    import argparse

    parser = argparse.ArgumentParser(description="Run inference with fine-tuned DeepSeek")
    parser.add_argument("--base-model", type=str, default="deepseek-ai/DeepSeek-R1-Distill-Qwen-1.5B")
    parser.add_argument("--lora-path", type=str, default=None, help="Path to LoRA adapter")
    parser.add_argument("--merged-path", type=str, default=None, help="Path to merged model")
    parser.add_argument("--prompt", type=str, default=None, help="Single prompt to run")
    parser.add_argument("--system-prompt", type=str, default=None, help="System prompt")
    parser.add_argument("--interactive", action="store_true", help="Run interactive chat")
    parser.add_argument("--no-4bit", action="store_true", help="Disable 4-bit quantization")

    args = parser.parse_args()

    model, tokenizer = load_model(
        base_model=args.base_model,
        lora_path=args.lora_path,
        merged_path=args.merged_path,
        load_in_4bit=not args.no_4bit,
    )

    if args.interactive or args.prompt is None:
        interactive_chat(model, tokenizer, system_prompt=args.system_prompt)
    else:
        response = generate_response(
            model,
            tokenizer,
            args.prompt,
            system_prompt=args.system_prompt,
        )
        print(f"Response: {response}")


if __name__ == "__main__":
    main()
