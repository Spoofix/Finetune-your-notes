"""
Convert markdown files to a JSONL training dataset for fine-tuning.

This script reads markdown files and creates conversation pairs suitable
for supervised fine-tuning of DeepSeek or similar models.
"""

import json
import os
import re
from pathlib import Path
from typing import Optional
from pydantic import BaseModel


class Message(BaseModel):
    role: str
    content: str


class Conversation(BaseModel):
    messages: list[Message]


def clean_markdown_content(content: str) -> str:
    """Clean up markdown content for training."""
    # Remove excessive whitespace
    content = re.sub(r'\n{3,}', '\n\n', content)
    # Remove image references
    content = re.sub(r'!\[.*?\]\(.*?\)', '', content)
    # Clean up arrows and special characters that are OCR artifacts
    content = re.sub(r'→', '', content)
    # Remove empty lines at start/end
    content = content.strip()
    return content


def extract_sections(content: str) -> list[tuple[str, str]]:
    """
    Extract sections from markdown content.
    Returns list of (heading, content) tuples.
    """
    sections = []

    # Split by headers (# ## ### etc)
    pattern = r'^(#{1,3})\s+(.+)$'
    lines = content.split('\n')

    current_heading = "Overview"
    current_content = []

    for line in lines:
        match = re.match(pattern, line)
        if match:
            # Save previous section if it has content
            if current_content:
                section_text = '\n'.join(current_content).strip()
                if section_text and len(section_text) > 50:  # Min length filter
                    sections.append((current_heading, section_text))
            current_heading = match.group(2)
            current_content = []
        else:
            current_content.append(line)

    # Don't forget the last section
    if current_content:
        section_text = '\n'.join(current_content).strip()
        if section_text and len(section_text) > 50:
            sections.append((current_heading, section_text))

    return sections


def create_conversations_from_markdown(
    file_path: Path,
    system_prompt: Optional[str] = None
) -> list[Conversation]:
    """
    Create training conversations from a markdown file.

    Strategies:
    1. Use section headings as questions, content as answers
    2. For long documents, create Q&A about the content
    """
    conversations = []

    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()

    content = clean_markdown_content(content)

    if len(content) < 100:
        return conversations

    filename = file_path.stem

    # Strategy 1: Section-based Q&A
    sections = extract_sections(content)

    for heading, section_content in sections:
        if len(section_content) < 50:
            continue

        # Create a question from the heading
        question = f"Tell me about {heading.lower()}"
        if heading.lower().startswith(('how', 'what', 'why', 'when', 'who')):
            question = heading

        messages = []
        if system_prompt:
            messages.append(Message(role="system", content=system_prompt))

        messages.extend([
            Message(role="user", content=question),
            Message(role="assistant", content=section_content)
        ])

        conversations.append(Conversation(messages=messages))

    # Strategy 2: Full document summary
    if len(content) > 200:
        question = f"What is the content of the document about {filename}?"

        # Truncate if too long (for training efficiency)
        truncated_content = content[:4000] if len(content) > 4000 else content

        messages = []
        if system_prompt:
            messages.append(Message(role="system", content=system_prompt))

        messages.extend([
            Message(role="user", content=question),
            Message(role="assistant", content=truncated_content)
        ])

        conversations.append(Conversation(messages=messages))

    return conversations


def process_markdown_files(
    input_dir: str,
    output_path: str,
    system_prompt: Optional[str] = None,
    file_limit: Optional[int] = None
) -> int:
    """
    Process all markdown files in directory and create training dataset.

    Args:
        input_dir: Directory containing markdown files
        output_path: Path to output JSONL file
        system_prompt: Optional system prompt to include
        file_limit: Optional limit on number of files to process

    Returns:
        Number of conversations created
    """
    input_path = Path(input_dir)
    md_files = list(input_path.rglob("*.md"))

    if file_limit:
        md_files = md_files[:file_limit]

    print(f"Found {len(md_files)} markdown files")

    all_conversations = []

    for file_path in md_files:
        try:
            conversations = create_conversations_from_markdown(
                file_path,
                system_prompt=system_prompt
            )
            all_conversations.extend(conversations)
            print(f"Processed {file_path.name}: {len(conversations)} conversations")
        except Exception as e:
            print(f"Error processing {file_path}: {e}")

    # Write to JSONL
    output_file = Path(output_path)
    output_file.parent.mkdir(parents=True, exist_ok=True)

    with open(output_file, 'w', encoding='utf-8') as f:
        for conv in all_conversations:
            # ensure_ascii=False keeps Russian/non-ASCII text readable
            f.write(json.dumps({"messages": [m.model_dump() for m in conv.messages]}, ensure_ascii=False) + '\n')

    print(f"\nCreated {len(all_conversations)} conversations in {output_path}")
    return len(all_conversations)


if __name__ == "__main__":
    import argparse

    parser = argparse.ArgumentParser(description="Convert markdown files to training dataset")
    parser.add_argument("--input-dir", type=str, required=True, help="Directory with markdown files")
    parser.add_argument("--output", type=str, default="data/training_data.jsonl", help="Output JSONL path")
    parser.add_argument("--system-prompt", type=str, default=None, help="Optional system prompt")
    parser.add_argument("--limit", type=int, default=None, help="Limit number of files")

    args = parser.parse_args()

    process_markdown_files(
        input_dir=args.input_dir,
        output_path=args.output,
        system_prompt=args.system_prompt,
        file_limit=args.limit
    )
