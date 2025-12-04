# Finetune Your Notes

Fine-tune DeepSeek models using your own markdown files as training data. Converts your notes/docs into high-quality Q&A pairs using Gemini, then trains a personalized LLM via [Tinker](https://github.com/thinking-machines-lab/tinker-cookbook) cloud GPUs.

## Repository Structure

```
Finetune-your-notes/
├── README.md                          # This file
├── requirements.txt                   # Python dependencies
├── .env.example                       # Environment variables template
├── .env                               # Your local env (DO NOT COMMIT)
│
├── convert_markdown_to_dataset.py    # Simple: Extract sections as Q&A
├── generate_training_data.py          # Advanced: Use Gemini to create Q&A pairs
│
├── train_deepseek_local.py            # Local training (Unsloth/HuggingFace)
├── train_tinker.py                    # Cloud training via Tinker API
│
├── inference.py                       # Local inference (LoRA models)
├── test_model.py                      # Cloud inference via Tinker API
│
└── data/                              # Training datasets (generated)
    ├── training_data.jsonl
    └── training_data_full.jsonl
```

---

## Quick Start

### 1. Clone and Setup

```bash
# Clone this repository
git clone https://github.com/OneInterface/Finetune-your-notes.git
cd Finetune-your-notes

# Create virtual environment
python -m venv .venv
source .venv/bin/activate  # On Windows: .venv\Scripts\activate

# Install dependencies
pip install -r requirements.txt

# Install tinker-cookbook (required for cloud training)
git clone https://github.com/thinking-machines-lab/tinker-cookbook.git
pip install -e ./tinker-cookbook

# Optional: Unsloth for faster local training (Linux/CUDA only)
pip install "unsloth[colab-new] @ git+https://github.com/unslothai/unsloth.git"
```

### 2. Configure Environment

```bash
cp .env.example .env
```

Edit `.env` with your API keys:
```bash
# For AI-generated training data
GEMINI_API_KEY=your-gemini-api-key

# For Tinker cloud training
TINKER_API_KEY=your-tinker-api-key
```

---

## Dataset Generation

### Method 1: Simple Extraction (No API)

Extracts markdown sections as Q&A pairs. Fast but lower quality.

```bash
# Basic usage
python convert_markdown_to_dataset.py \
    --input-dir /path/to/your/markdown/files \
    --output data/training_data.jsonl

# Limit number of files (for testing)
python convert_markdown_to_dataset.py \
    --input-dir /path/to/your/markdown/files \
    --output data/training_data.jsonl \
    --limit 10

# With system prompt
python convert_markdown_to_dataset.py \
    --input-dir /path/to/your/markdown/files \
    --output data/training_data.jsonl \
    --system-prompt "You are a helpful assistant with knowledge about our company."
```

### Method 2: AI-Generated Q&A (Recommended)

Uses Gemini to create diverse, high-quality Q&A pairs. Requires `GEMINI_API_KEY`.

```bash
# Test with a single file first
python generate_training_data.py \
    --test /path/to/sample.md

# Example: test with specific file
python generate_training_data.py \
    --test /Users/stefan/Programming/prompts/stormy/generated/outreach.md

# Process ALL files in directory (50 concurrent by default)
python generate_training_data.py --all

# Process all with custom paths
python generate_training_data.py \
    --all \
    --prompts-dir /path/to/your/markdown/files \
    --output data/training_data_full.jsonl

# Test with random file (default behavior)
python generate_training_data.py \
    --prompts-dir /path/to/your/markdown/files
```

### Dataset Format

Generated JSONL format:
```jsonl
{"messages": [{"role": "user", "content": "What is X?"}, {"role": "assistant", "content": "X is..."}]}
{"messages": [{"role": "user", "content": "How does Y work?"}, {"role": "assistant", "content": "Y works by..."}]}
```

---

## Training Options

You have two options for training:

| Option | Best For | Requirements |
|--------|----------|--------------|
| **Local** | Testing, small datasets, no cloud dependency | GPU with 4-24GB VRAM |
| **Tinker Cloud** | Production, large models, fast training | TINKER_API_KEY |

---

## Option A: Local Training

### Available Models

| Model | Parameters | VRAM (4-bit) | Best For |
|-------|------------|--------------|----------|
| `deepseek-ai/DeepSeek-R1-Distill-Qwen-1.5B` | 1.5B | ~4GB | Testing |
| `deepseek-ai/DeepSeek-R1-Distill-Llama-8B` | 8B | ~8GB | Good balance |
| `deepseek-ai/DeepSeek-R1-Distill-Qwen-14B` | 14B | ~12GB | Better quality |
| `deepseek-ai/DeepSeek-R1-Distill-Qwen-32B` | 32B | ~24GB | High quality |

### Training Commands

```bash
# Quick test (50 steps)
python train_deepseek_local.py \
    --model deepseek-ai/DeepSeek-R1-Distill-Qwen-1.5B \
    --dataset data/training_data.jsonl \
    --output-dir ./outputs/quick-test \
    --max-steps 50 \
    --batch-size 1

# Full training (3 epochs)
python train_deepseek_local.py \
    --model deepseek-ai/DeepSeek-R1-Distill-Qwen-1.5B \
    --dataset data/training_data.jsonl \
    --output-dir ./outputs/deepseek-finetuned \
    --epochs 3 \
    --batch-size 2

# Advanced options
python train_deepseek_local.py \
    --model deepseek-ai/DeepSeek-R1-Distill-Qwen-1.5B \
    --dataset data/training_data.jsonl \
    --output-dir ./outputs/custom-run \
    --epochs 5 \
    --batch-size 4 \
    --lr 1e-4 \
    --lora-r 32 \
    --max-seq-length 4096 \
    --no-4bit \
    --use-standard
```

### Local Training Parameters

| Parameter | Default | Description |
|-----------|---------|-------------|
| `--model` | `DeepSeek-R1-Distill-Qwen-1.5B` | Model to fine-tune |
| `--dataset` | Required | Path to JSONL dataset |
| `--output-dir` | `./outputs` | Where to save the model |
| `--epochs` | `3` | Number of training epochs |
| `--max-steps` | `-1` | Max steps (-1 = full epochs) |
| `--batch-size` | `2` | Batch size per device |
| `--lr` | `2e-4` | Learning rate |
| `--lora-r` | `16` | LoRA rank |
| `--max-seq-length` | `2048` | Max sequence length |
| `--no-4bit` | `False` | Disable 4-bit quantization |
| `--use-standard` | `False` | Skip Unsloth, use HuggingFace |

### Local Inference

```bash
# Interactive chat with LoRA adapter
python inference.py \
    --lora-path ./outputs/deepseek-finetuned \
    --interactive

# Single prompt
python inference.py \
    --lora-path ./outputs/deepseek-finetuned \
    --prompt "What do you know about our product?"

# With system prompt
python inference.py \
    --lora-path ./outputs/deepseek-finetuned \
    --system-prompt "You are a helpful marketing assistant." \
    --interactive

# Use merged model (faster loading)
python inference.py \
    --merged-path ./outputs/deepseek-finetuned_merged \
    --prompt "Tell me about the marketing strategy"
```

---

## Option B: Tinker Cloud Training (Recommended for Production)

[Tinker](https://github.com/thinking-machines-lab/tinker-cookbook) provides hosted GPU infrastructure for fast, scalable fine-tuning.

**Setup:**
1. Sign up through the [Tinker waitlist](https://thinkingmachines.ai/tinker)
2. Create an API key from the [Tinker console](https://tinker-console.thinkingmachines.ai)
3. Set `TINKER_API_KEY` in your `.env`
4. Install tinker-cookbook (already done if you followed Quick Start):
   ```bash
   git clone https://github.com/thinking-machines-lab/tinker-cookbook.git
   pip install -e ./tinker-cookbook
   ```

**Tinker Basics:**
```python
import tinker

# Create service client
service_client = tinker.ServiceClient()

# Create training client
training_client = service_client.create_lora_training_client(
    base_model="meta-llama/Llama-3.2-1B",
    rank=32,
)

# Training operations
training_client.forward_backward(...)
training_client.optim_step(...)

# Get sampling client for inference
sampling_client = training_client.save_weights_and_get_sampling_client(name="my_model")
sampling_client.sample(...)
```

See the [Tinker docs](https://tinker-docs.thinkingmachines.ai/training-sampling) for more details.

### Supported Models

| Model | Description |
|-------|-------------|
| `deepseek-ai/DeepSeek-V3.1` | Latest DeepSeek model |
| `meta-llama/Llama-3.1-8B` | Llama 3.1 8B |
| `meta-llama/Llama-3.1-70B` | Llama 3.1 70B |

### Training Commands

```bash
# Basic training
python train_tinker.py \
    --dataset data/training_data.jsonl \
    --model "meta-llama/Llama-3.1-8B" \
    --epochs 1

# Full production training with DeepSeek V3.1
python train_tinker.py \
    --dataset data/training_data_full.jsonl \
    --model "deepseek-ai/DeepSeek-V3.1" \
    --epochs 5 \
    --batch-size 4 \
    --log-path /tmp/tinker-examples/deepseek-full-training

# Continue from checkpoint
python train_tinker.py \
    --dataset data/training_data.jsonl \
    --model "deepseek-ai/DeepSeek-V3.1" \
    --epochs 3 \
    --checkpoint "tinker://your-previous-run/sampler_weights/001000"
```

### Tinker Training Parameters

| Parameter | Default | Description |
|-----------|---------|-------------|
| `--dataset` | `data/training_data.jsonl` | Path to JSONL dataset |
| `--model` | `meta-llama/Llama-3.1-8B` | Base model to fine-tune |
| `--log-path` | `/tmp/tinker-examples/...` | Path for logs/checkpoints |
| `--epochs` | `1` | Number of training epochs |
| `--lr` | `2e-4` | Learning rate |
| `--batch-size` | `32` | Batch size |
| `--max-length` | `4096` | Max sequence length |
| `--checkpoint` | `None` | Continue from checkpoint |

### Tinker Cloud Inference

Test your cloud-trained model:

```bash
# Single prompt
python test_model.py \
    --model "tinker://876021e0-2d1f-5c57-a1e1-1be2b60ae134:train:0/sampler_weights/001400" \
    --base-model "deepseek-ai/DeepSeek-V3.1" \
    --prompt "Tell me what do you think about gen AI and marketing? Go deep into details of why"

# Interactive chat
python test_model.py \
    --model "tinker://your-run-id/sampler_weights/final" \
    --base-model "deepseek-ai/DeepSeek-V3.1" \
    --interactive

# Using defaults from .env
python test_model.py --prompt "What are our key differentiators?"
```

### Model Path Format

Tinker model paths follow this format:
```
tinker://<run-id>:train:<step>/sampler_weights/<checkpoint>
```

Examples:
- `tinker://876021e0-2d1f-5c57-a1e1-1be2b60ae134:train:0/sampler_weights/001400`
- `tinker://my-run-id/sampler_weights/final`

---

## Full Pipeline Examples

### Example 1: Quick Local Test

```bash
# 1. Generate small dataset
python convert_markdown_to_dataset.py \
    --input-dir ~/Documents/notes \
    --output data/training_data.jsonl \
    --limit 5

# 2. Quick training (50 steps)
python train_deepseek_local.py \
    --model deepseek-ai/DeepSeek-R1-Distill-Qwen-1.5B \
    --dataset data/training_data.jsonl \
    --output-dir ./outputs/test \
    --max-steps 50

# 3. Test
python inference.py \
    --lora-path ./outputs/test \
    --interactive
```

### Example 2: Production with Tinker

```bash
# 1. Generate high-quality dataset with Gemini
python generate_training_data.py --all

# 2. Train on Tinker cloud with DeepSeek V3.1
python train_tinker.py \
    --dataset data/training_data_full.jsonl \
    --model "deepseek-ai/DeepSeek-V3.1" \
    --epochs 5 \
    --batch-size 4 \
    --log-path /tmp/tinker-examples/deepseek-full-training

# 3. Test the trained model
python test_model.py \
    --model "tinker://YOUR_RUN_ID/sampler_weights/final" \
    --base-model "deepseek-ai/DeepSeek-V3.1" \
    --prompt "What insights can you share about our business?"
```

### Example 3: Iterate on Quality

```bash
# 1. Test single file first
python generate_training_data.py \
    --test /Users/stefan/Programming/prompts/stormy/generated/outreach.md

# 2. If quality looks good, process all
python generate_training_data.py --all

# 3. Train
python train_tinker.py \
    --dataset data/training_data_full.jsonl \
    --model "deepseek-ai/DeepSeek-V3.1" \
    --epochs 5

# 4. Test specific knowledge
python test_model.py \
    --model "tinker://YOUR_RUN_ID/sampler_weights/final" \
    --base-model "deepseek-ai/DeepSeek-V3.1" \
    --prompt "Tell me what do you think about gen AI and marketing? Go deep into details of why"
```

---

## Output Structure

### Local Training Output
```
outputs/
└── my-model/
    ├── adapter_config.json
    ├── adapter_model.safetensors
    ├── tokenizer.json
    ├── tokenizer_config.json
    └── checkpoint-*/

outputs/
└── my-model_merged/          # If using Unsloth
    └── (full merged model)
```

### Tinker Training Output
```
/tmp/tinker-examples/your-run/
├── metrics.jsonl             # Training metrics
├── checkpoints.jsonl         # Checkpoint registry
└── logs/                     # Detailed logs
```

Model weights are stored in Tinker cloud and accessed via:
```
tinker://<run-id>/sampler_weights/<step>
```

---

## Tips

1. **Start small**: Test with 50 steps and 1.5B model locally before scaling
2. **Quality > Quantity**: 100 good Q&A pairs beat 1000 poor ones
3. **Use Gemini**: AI-generated data is much higher quality than simple extraction
4. **Tinker for production**: Cloud training is faster and handles large models
5. **Iterate**: Generate → Train → Test → Refine → Repeat

## Troubleshooting

**Out of Memory (OOM) - Local**
- Reduce `--batch-size` to 1
- Use smaller model (1.5B)
- Reduce `--max-seq-length`

**Slow training on Mac**
- MPS backend is slower than CUDA
- Consider Tinker cloud for production

**bitsandbytes errors**
- Only works on Linux/CUDA
- Mac will use full precision automatically

**Tinker auth errors**
- Check `TINKER_API_KEY` in `.env`
- Ensure API key has training permissions

**Poor model quality**
- Generate more diverse Q&A pairs with Gemini
- Increase training epochs
- Review and clean training data
- Use higher LoRA rank (`--lora-r 32`)

---

## References

- **Tinker Cookbook**: https://github.com/thinking-machines-lab/tinker-cookbook
- **DeepSeek Models**: https://huggingface.co/deepseek-ai
- **Unsloth**: https://github.com/unslothai/unsloth
- **Google Gemini API**: https://ai.google.dev/
