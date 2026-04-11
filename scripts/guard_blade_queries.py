#!/usr/bin/env python3
"""
Pass 3: Wrap @php blocks in Blade views that call Eloquent models without guards.

Strategy: find @php blocks that contain model query calls (::first(), ::all(), ::get(),
::where()) and are NOT already wrapped in try/catch or Schema::hasTable guards.
Wrap them in try/catch to prevent crashes if tables are missing.
"""

import os
import re
from pathlib import Path

BASE = Path('/home/user/Worksuite-Saas---Project-Management-System_Laravel/Modules')

STATS = {
    'files_checked': 0,
    'files_patched': 0,
    'blocks_patched': 0,
}

# Patterns that indicate a direct model query
QUERY_PATTERNS = [
    r'::(first|all|get|find|where|pluck|count|latest|oldest)\s*\(',
    r'->(first|all|get|find|where|pluck|count|latest|oldest)\s*\(',
]

def has_query(block_content):
    for p in QUERY_PATTERNS:
        if re.search(p, block_content):
            return True
    return False

def already_guarded(block_content):
    return (
        'try {' in block_content or
        'try{' in block_content or
        'Schema::hasTable' in block_content or
        'schema()->hasTable' in block_content.lower()
    )

def patch_file(path):
    text = path.read_text(encoding='utf-8', errors='replace')

    if '::first()' not in text and '::all()' not in text and '->first()' not in text:
        return False

    # Find @php ... @endphp blocks
    pattern = re.compile(r'(@php\b)(.*?)(@endphp)', re.DOTALL)

    def replace_block(m):
        open_tag = m.group(1)
        content = m.group(2)
        close_tag = m.group(3)

        if not has_query(content):
            return m.group(0)
        if already_guarded(content):
            return m.group(0)

        # Wrap the content in a try/catch
        STATS['blocks_patched'] += 1
        wrapped_content = f"\n    try {{\n{content}\n    }} catch (\\Exception $e) {{\n        // Table may not exist yet\n    }}\n"
        return open_tag + wrapped_content + close_tag

    new_text = pattern.sub(replace_block, text)

    if new_text != text:
        path.write_text(new_text, encoding='utf-8')
        STATS['files_patched'] += 1
        return True
    return False


def main():
    blade_files = list(BASE.rglob('*.blade.php'))
    print(f"Checking {len(blade_files)} Blade files...")

    for f in blade_files:
        STATS['files_checked'] += 1
        try:
            patch_file(f)
        except Exception as e:
            print(f"  Error: {f}: {e}")

    print(f"\nResults:")
    print(f"  Files checked: {STATS['files_checked']}")
    print(f"  Files patched: {STATS['files_patched']}")
    print(f"  @php blocks wrapped in try/catch: {STATS['blocks_patched']}")


if __name__ == '__main__':
    main()
