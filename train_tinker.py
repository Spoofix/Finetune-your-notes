"""
Fine-tune using Tinker's hosted API (cloud GPUs).

This uses the Tinker service for fast, scalable fine-tuning.
Make sure you have TINKER_API_KEY set in your environment.

Usage:
    python train_tinker.py --dataset data/training_data.jsonl
"""

import chz
import sys
import asyncio
from pathlib import Path

from tinker_cookbook import cli_utils, model_info
from tinker_cookbook.renderers import TrainOnWhat
from tinker_cookbook.supervised import train
from tinker_cookbook.supervised.data import FromConversationFileBuilder
from tinker_cookbook.supervised.types import ChatDatasetBuilderCommonConfig


def build_config(
    dataset_path: str,
    model_name: str = "meta-llama/Llama-3.1-8B",
    log_path: str = "/tmp/tinker-examples/markdown-finetune",
    num_epochs: int = 1,
    learning_rate: float = 2e-4,
    batch_size: int = 32,
    max_length: int = 4096,
    load_checkpoint_path: str | None = None,
) -> train.Config:
    """Build training config for Tinker API."""

    renderer_name = model_info.get_recommended_renderer_name(model_name)

    common_config = ChatDatasetBuilderCommonConfig(
        model_name_for_tokenizer=model_name,
        renderer_name=renderer_name,
        max_length=max_length,
        batch_size=batch_size,
        train_on_what=TrainOnWhat.ALL_ASSISTANT_MESSAGES,
    )

    # Use your custom dataset
    dataset = FromConversationFileBuilder(
        common_config=common_config,
        file_path=dataset_path,
    )

    config_dict = {
        "log_path": log_path,
        "model_name": model_name,
        "dataset_builder": dataset,
        "learning_rate": learning_rate,
        "lr_schedule": "linear",
        "num_epochs": num_epochs,
        "eval_every": 8,
    }
    if load_checkpoint_path:
        config_dict["load_checkpoint_path"] = load_checkpoint_path

    blueprint = chz.Blueprint(train.Config).apply(config_dict)

    return blueprint.make()


async def run_training(config: train.Config):
    """Run training on Tinker cloud."""
    await train.main(config)


def main():
    import argparse

    parser = argparse.ArgumentParser(description="Fine-tune on Tinker cloud")
    parser.add_argument(
        "--dataset",
        type=str,
        default="data/training_data.jsonl",
        help="Path to JSONL dataset"
    )
    parser.add_argument(
        "--model",
        type=str,
        default="meta-llama/Llama-3.1-8B",
        help="Base model to fine-tune"
    )
    parser.add_argument(
        "--log-path",
        type=str,
        default="/tmp/tinker-examples/markdown-finetune",
        help="Path for logs and checkpoints"
    )
    parser.add_argument("--epochs", type=int, default=1)
    parser.add_argument("--lr", type=float, default=2e-4)
    parser.add_argument("--batch-size", type=int, default=32)
    parser.add_argument("--max-length", type=int, default=4096)
    parser.add_argument(
        "--checkpoint",
        type=str,
        default=None,
        help="Path to checkpoint to continue training from (e.g., tinker://path/to/checkpoint)"
    )

    args = parser.parse_args()

    # Check dataset exists
    if not Path(args.dataset).exists():
        print(f"Error: Dataset not found at {args.dataset}")
        print("Run convert_markdown_to_dataset.py first!")
        sys.exit(1)

    config = build_config(
        dataset_path=args.dataset,
        model_name=args.model,
        log_path=args.log_path,
        num_epochs=args.epochs,
        learning_rate=args.lr,
        batch_size=args.batch_size,
        max_length=args.max_length,
        load_checkpoint_path=args.checkpoint,
    )

    # Check log dir
    cli_utils.check_log_dir(config.log_path, behavior_if_exists="ask")

    print(f"Starting Tinker cloud training...")
    print(f"  Model: {args.model}")
    print(f"  Dataset: {args.dataset}")
    print(f"  Epochs: {args.epochs}")
    print(f"  Learning rate: {args.lr}")
    if args.checkpoint:
        print(f"  Loading weights from: {args.checkpoint}")

    asyncio.run(run_training(config))

    print("\nTraining complete!")
    print(f"Logs saved to: {config.log_path}")


if __name__ == "__main__":
    main()
