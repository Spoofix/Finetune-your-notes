"""
Generate high-quality training data from markdown files using Gemini API.
Creates diverse Q&A pairs for each document.
"""

import asyncio
import json
import os
from pathlib import Path

import dotenv
import google.generativeai as genai

dotenv.load_dotenv(Path(__file__).parent / ".env")

GEMINI_API_KEY = os.getenv("GEMINI_API_KEY")
genai.configure(api_key=GEMINI_API_KEY)

PROMPT_TEMPLATE = """You are creating training data for a personal AI assistant that knows everything about the user's business, meetings, and notes.

Given this document, generate question-answer pairs that would help an AI deeply learn this knowledge.

LESS IS MORE: Only generate 3-8 pairs. If the document doesn't have good extractable insights, generate fewer or even just 1-2. Don't force bad questions just to hit a number!

YOUR APPROACH:
- Think about the CONCEPTS, TRENDS, and APPROACHES discussed in the document
- Ask about PARTS of solutions, not the whole thing
- Questions should prompt THINKING and REASONING about the problem space
- Focus on extracting insights, not summarizing everything

MOST CRITICAL RULE: USE THE USER'S EXACT WORDS FROM THE TEXT!
- COPY phrases VERBATIM from the document - NEVER summarize or paraphrase!
- The answer should be a direct quote or near-direct quote from the text
- If the user wrote "deep integration plus a large set of prepaid agents" - use those EXACT words
- If there's related constext elsewhere in the document, include that too!

QUESTION STYLE - Just enough context, not too much:
- Include the project/product name so we know what domain
- Ask about ONE concept, trend, or part of the solution
- Don't overload with details
- IMPORTANT: The model will be trained WITHOUT the original document! So the question MUST contain enough context about WHAT we're discussing.
- CRITICAL: Distinguish between OUR company/projects vs CLIENTS! If the doc discusses a client, NAME THE CLIENT explicitly!

BAD QUESTIONS:
- "What are some mitigations proposed for abuse vectors?" ❌ (abuse vectors for WHAT? no context!)
- "What product split strategy does the company plan to use?" ❌ (WHICH company? ours or a client?)
- "What is the main goal?" ❌ (too vague, no context)
- "What are all the steps for the entire strategy?" ❌ (asking for too much)

GOOD QUESTIONS (balanced - has context but asks about ONE thing):
- "For our client Calypso (the THC drink brand), what's their product split strategy?" ✅ (clarifies it's a CLIENT)
- "For the Stormy influencer platform, what are some mitigations for abuse vectors?" ✅
- "What's the real moat for Stormy?" ✅
- "For Stormy, what would separate us from competitors?" ✅
- "In the TikTok video automation project, what's the approach for Spark Ads?" ✅

ANSWER RULES:
1. USE THE USER'S EXACT WORDS - copy directly from text, NEVER summarize!
2. Keep answers SHORT and punchy (1-3 sentences usually)
3. If related context exists elsewhere in the document, ADD IT to the answer
4. Preserve casual tone - if they wrote "i think" keep it lowercase "i"
5. If the document says "this is fucking important" - say exactly that
6. NO MARKDOWN EVER - no bullet points, no headers, no bold/italic. Just raw plain text.

EXAMPLE:
Document says: "i think deep integration plus a large set of prepaid agents with accesses like to semruch or smth else is what would separate us"
And elsewhere: "Deep Integration Play - Webhooks, analytics, attribution embedded in customer systems"

Question: "What would separate Stormy from competitors?"
Answer: "i think deep integration plus a large set of prepaid agents with accesses like to semruch or smth else is what would separate us. The deep integration play means webhooks, analytics, attribution embedded in customer systems - become infrastructure layer, not external tool."

Document content:
---
{content}
---

Return ONLY a JSON array of objects with this exact format (no markdown, no code blocks):
[
  {{"user": "long contextual question with platform/project/specifics mentioned", "assistant": "detailed answer"}},
  {{"user": "another rich context question", "assistant": "another detailed answer"}}
]
"""


def generate_qa_pairs(content: str, filename: str, retries: int = 3) -> list[dict]:
    """Use Gemini to generate Q&A pairs from document content."""

    model = genai.GenerativeModel("gemini-3-pro-preview")

    prompt = PROMPT_TEMPLATE.format(content=content[:15000])  # Limit content length

    for attempt in range(retries):
        try:
            response = model.generate_content(prompt)
            text = response.text.strip()

            # Clean up response - remove markdown code blocks if present
            if "```" in text:
                # Extract content between code blocks
                parts = text.split("```")
                for part in parts:
                    part = part.strip()
                    if part.startswith("json"):
                        part = part[4:].strip()
                    if part.startswith("["):
                        text = part
                        break

            text = text.strip()

            # Fix common JSON issues
            text = text.replace("\n", " ").replace("\r", " ")

            # Try to find the JSON array
            start = text.find("[")
            end = text.rfind("]") + 1
            if start != -1 and end > start:
                text = text[start:end]

            qa_pairs = json.loads(text)

            # Convert to messages format
            messages_list = []
            for qa in qa_pairs:
                if "user" in qa and "assistant" in qa:
                    messages_list.append({
                        "messages": [
                            {"role": "user", "content": qa["user"]},
                            {"role": "assistant", "content": qa["assistant"]}
                        ]
                    })

            return messages_list

        except json.JSONDecodeError as e:
            if attempt < retries - 1:
                continue  # Retry silently
            print(f"  JSON error {filename}: {e}")
            return []
        except Exception as e:
            if attempt < retries - 1:
                continue
            print(f"  Error {filename}: {e}")
            return []

    return []


def process_single_file(file_path: str) -> list[dict]:
    """Process a single markdown file and return training data."""

    with open(file_path, "r", encoding="utf-8") as f:
        content = f.read()

    if len(content.strip()) < 100:
        print(f"  Skipping {file_path} - too short")
        return []

    filename = Path(file_path).name
    print(f"Processing: {filename}")

    qa_pairs = generate_qa_pairs(content, filename)
    print(f"  Generated {len(qa_pairs)} Q&A pairs")

    return qa_pairs


async def process_single_file_async(file_path: str, semaphore) -> list[dict]:
    """Process a single markdown file asynchronously."""
    async with semaphore:
        return await asyncio.to_thread(process_single_file, file_path)


async def process_all_files_async(prompts_dir: str, output_file: str, max_concurrent: int = 50):
    """Process all markdown files in parallel."""
    import asyncio

    md_files = list(Path(prompts_dir).rglob("*.md"))
    print(f"Found {len(md_files)} markdown files")
    print(f"Processing with {max_concurrent} concurrent requests...\n")

    semaphore = asyncio.Semaphore(max_concurrent)

    tasks = [process_single_file_async(str(f), semaphore) for f in md_files]
    results = await asyncio.gather(*tasks)

    all_training_data = []
    for qa_pairs in results:
        all_training_data.extend(qa_pairs)

    # Write to JSONL
    with open(output_file, "w", encoding="utf-8") as f:
        for item in all_training_data:
            f.write(json.dumps(item, ensure_ascii=False) + "\n")

    print(f"\n\nDone! Created {len(all_training_data)} training examples")
    print(f"Saved to: {output_file}")


def process_all_files(prompts_dir: str, output_file: str):
    """Process all markdown files in parallel."""
    asyncio.run(process_all_files_async(prompts_dir, output_file))


def test_single_file(file_path: str):
    """Test processing a single file and show results."""

    qa_pairs = process_single_file(file_path)

    print("\n" + "="*60)
    print("GENERATED TRAINING DATA:")
    print("="*60)

    for i, item in enumerate(qa_pairs):
        print(f"\n--- Example {i+1} ---")
        print(f"USER: {item['messages'][0]['content']}")
        print(f"ASSISTANT: {item['messages'][1]['content'][:300]}...")

    return qa_pairs


if __name__ == "__main__":
    import argparse

    parser = argparse.ArgumentParser()
    parser.add_argument("--test", type=str, help="Test with a single file")
    parser.add_argument("--all", action="store_true", help="Process all files")
    parser.add_argument("--prompts-dir", type=str, default="/Users/stefan/Programming/prompts")
    parser.add_argument("--output", type=str, default="data/training_data_full.jsonl")

    args = parser.parse_args()

    if args.test:
        test_single_file(args.test)
    elif args.all:
        process_all_files(args.prompts_dir, args.output)
    else:
        # Default: test with a random file
        import random
        md_files = list(Path(args.prompts_dir).rglob("*.md"))
        test_file = random.choice(md_files)
        print(f"Testing with random file: {test_file}\n")
        test_single_file(str(test_file))
