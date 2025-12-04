"""
Test your fine-tuned model via Tinker API.
"""

import asyncio
import os
from pathlib import Path

import dotenv

# Load .env from this directory
dotenv.load_dotenv(Path(__file__).parent / ".env")

import tinker
from tinker import types
from tinker_cookbook import renderers
from tinker_cookbook.model_info import get_recommended_renderer_name
from tinker_cookbook.tokenizer_utils import get_tokenizer

os.environ["TOKENIZERS_PARALLELISM"] = "false"

# Defaults from .env
DEFAULT_MODEL_PATH = os.getenv("MODEL_PATH", "")
DEFAULT_BASE_MODEL = os.getenv("BASE_MODEL", "deepseek-ai/DeepSeek-V3.1")


async def chat_with_model(
    model_path: str,
    base_model: str,
    prompt: str,
    max_tokens: int = 512,
    temperature: float = 0.7,
):
    """Send a prompt to your fine-tuned model."""
    service_client = tinker.ServiceClient()

    # Create sampling client with base model and fine-tuned weights
    sampling_client = service_client.create_sampling_client(
        base_model=base_model,
        model_path=model_path,
    )

    # Get tokenizer and renderer
    tokenizer = get_tokenizer(base_model)
    renderer = renderers.get_renderer(
        get_recommended_renderer_name(base_model), tokenizer
    )

    # Build the model input
    messages = [{"role": "user", "content": prompt}]
    model_input = renderer.build_generation_prompt(messages)

    # Set up sampling parameters
    sampling_params = types.SamplingParams(
        max_tokens=max_tokens,
        temperature=temperature,
        stop=renderer.get_stop_sequences(),
    )

    # Generate response
    response = await sampling_client.sample_async(
        prompt=model_input,
        num_samples=1,
        sampling_params=sampling_params,
    )

    # Parse the response
    parsed_message, _ = renderer.parse_response(response.sequences[0].tokens)
    return parsed_message["content"]


async def interactive_chat(model_path: str, base_model: str):
    """Interactive chat session."""
    print("\n" + "=" * 50)
    print("Chat with your fine-tuned DeepSeek model")
    print("Type 'quit' to exit")
    print("=" * 50 + "\n")

    service_client = tinker.ServiceClient()
    sampling_client = service_client.create_sampling_client(
        base_model=base_model,
        model_path=model_path,
    )

    tokenizer = get_tokenizer(base_model)
    renderer = renderers.get_renderer(
        get_recommended_renderer_name(base_model), tokenizer
    )

    conversation = []

    while True:
        try:
            user_input = input("You: ").strip()
            if user_input.lower() in ['quit', 'exit', 'q']:
                print("Goodbye!")
                break

            if not user_input:
                continue

            conversation.append({"role": "user", "content": user_input})

            print("Thinking...")
            model_input = renderer.build_generation_prompt(conversation)

            sampling_params = types.SamplingParams(
                max_tokens=512,
                temperature=0.7,
                stop=renderer.get_stop_sequences(),
            )

            response = await sampling_client.sample_async(
                prompt=model_input,
                num_samples=1,
                sampling_params=sampling_params,
            )

            parsed_message, _ = renderer.parse_response(response.sequences[0].tokens)
            assistant_response = parsed_message["content"]

            conversation.append({"role": "assistant", "content": assistant_response})
            print(f"\nAssistant: {assistant_response}\n")

        except KeyboardInterrupt:
            print("\nGoodbye!")
            break


def main():
    import argparse

    parser = argparse.ArgumentParser(description="Test your fine-tuned Tinker model")
    parser.add_argument(
        "--model",
        type=str,
        default=None,  # Uses MODEL_PATH from .env
        help="Tinker model path"
    )
    parser.add_argument(
        "--base-model",
        type=str,
        default="deepseek-ai/DeepSeek-V3.1",
        help="Base model name"
    )
    parser.add_argument("--prompt", type=str, default=None, help="Single prompt")
    parser.add_argument("--interactive", "-i", action="store_true", help="Interactive mode")

    args = parser.parse_args()

    # Use env defaults if not specified
    model_path = args.model or DEFAULT_MODEL_PATH
    base_model = args.base_model or DEFAULT_BASE_MODEL

    if args.prompt:
        response = asyncio.run(chat_with_model(
            model_path, base_model, args.prompt
        ))
        print(f"Response: {response}")
    else:
        asyncio.run(interactive_chat(model_path, base_model))


if __name__ == "__main__":
    main()
