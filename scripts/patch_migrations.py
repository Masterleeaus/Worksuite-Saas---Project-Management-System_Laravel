#!/usr/bin/env python3
"""
Patch all module migration files to be idempotent.

Pass 1a: Add Schema::hasTable() guard before Schema::create() calls
Pass 1b: Add Schema::hasTable() guard before Schema::table() calls (alter migrations)
"""

import os
import re
import sys
from pathlib import Path

BASE = Path('/home/user/Worksuite-Saas---Project-Management-System_Laravel/Modules')

STATS = {
    'already_guarded': 0,
    'patched_create': 0,
    'patched_table': 0,
    'mixed': 0,
    'skipped_down_only': 0,
    'errors': [],
}

def extract_table_name(line):
    """Extract table name from Schema::create/table call."""
    m = re.search(r"Schema::\w+\(\s*['\"]([^'\"]+)['\"]", line)
    return m.group(1) if m else None


def find_up_body_start(lines):
    """Find the line index of the opening brace of the up() method."""
    in_up = False
    for i, line in enumerate(lines):
        if re.search(r'public\s+function\s+up\s*\(', line):
            in_up = True
        if in_up and '{' in line:
            return i
    return -1


def patch_file(path):
    text = path.read_text(encoding='utf-8', errors='replace')

    # Skip if already has hasTable guard
    if 'Schema::hasTable' in text or 'Schema::hasColumn' in text:
        STATS['already_guarded'] += 1
        return False

    # Skip if no up() method
    if 'public function up' not in text:
        STATS['skipped_down_only'] += 1
        return False

    lines = text.splitlines(keepends=True)

    # Find positions of Schema::create and Schema::table inside up()
    # Strategy: insert guard at the first Schema::create / Schema::table call inside up()

    # First, find the up() method body
    up_start = -1
    brace_depth = 0
    in_up = False
    up_body_start = -1
    up_body_end = -1

    for i, line in enumerate(lines):
        if re.search(r'public\s+function\s+up\s*\(', line):
            in_up = True

        if in_up:
            for ch in line:
                if ch == '{':
                    brace_depth += 1
                    if brace_depth == 1 and up_body_start == -1:
                        up_body_start = i
                elif ch == '}':
                    brace_depth -= 1
                    if brace_depth == 0 and up_body_start != -1:
                        up_body_end = i
                        in_up = False
                        break
        if up_body_end != -1:
            break

    if up_body_start == -1 or up_body_end == -1:
        STATS['errors'].append(f"Could not find up() bounds: {path}")
        return False

    # Find first Schema::create or Schema::table within up() body
    create_line = -1
    table_line = -1
    create_table_name = None
    table_table_name = None

    for i in range(up_body_start + 1, up_body_end):
        line = lines[i]
        if create_line == -1 and re.search(r'Schema::create\s*\(', line):
            create_line = i
            create_table_name = extract_table_name(line)
        if table_line == -1 and re.search(r'Schema::table\s*\(', line):
            table_line = i
            table_table_name = extract_table_name(line)
        if create_line != -1 and table_line != -1:
            break

    if create_line == -1 and table_line == -1:
        STATS['skipped_down_only'] += 1
        return False

    # Determine indentation of the first Schema call
    first_line_idx = min(
        x for x in [create_line, table_line] if x != -1
    )
    first_line = lines[first_line_idx]
    indent = re.match(r'^(\s*)', first_line).group(1)

    # Build the guard to insert
    # If there's a Schema::create, guard with hasTable (return if already exists)
    # If there's a Schema::table (alter), guard with !hasTable (return if not exists)

    inserts = []

    if create_line != -1 and create_table_name:
        guard = (
            f"{indent}if (Schema::hasTable('{create_table_name}')) {{\n"
            f"{indent}    return;\n"
            f"{indent}}}\n"
        )
        inserts.append((create_line, guard))
        STATS['patched_create'] += 1

    if table_line != -1 and table_table_name:
        # If we also inserted a create guard, shift the table_line by 3
        offset = 3 if (create_line != -1 and table_line > create_line) else 0
        guard = (
            f"{indent}if (! Schema::hasTable('{table_table_name}')) {{\n"
            f"{indent}    return;\n"
            f"{indent}}}\n"
        )
        inserts.append((table_line + offset, guard))
        if create_line != -1:
            STATS['mixed'] += 1
        else:
            STATS['patched_table'] += 1

    # Apply inserts in reverse order so indices stay valid
    for insert_idx, guard_text in sorted(inserts, key=lambda x: x[0], reverse=True):
        lines.insert(insert_idx, guard_text)

    new_text = ''.join(lines)
    path.write_text(new_text, encoding='utf-8')
    return True


def main():
    migration_files = list(BASE.rglob('*/Migrations/*.php')) + list(BASE.rglob('*/migrations/*.php'))
    print(f"Found {len(migration_files)} migration files")

    patched = 0
    for f in sorted(migration_files):
        try:
            if patch_file(f):
                patched += 1
        except Exception as e:
            STATS['errors'].append(f"{f}: {e}")

    print(f"\nResults:")
    print(f"  Already guarded (skipped): {STATS['already_guarded']}")
    print(f"  Patched create migrations: {STATS['patched_create']}")
    print(f"  Patched table/alter migrations: {STATS['patched_table']}")
    print(f"  Mixed (both create+table): {STATS['mixed']}")
    print(f"  Skipped (no schema ops / no up()): {STATS['skipped_down_only']}")
    print(f"  Total files patched: {patched}")
    if STATS['errors']:
        print(f"\nErrors ({len(STATS['errors'])}):")
        for e in STATS['errors'][:20]:
            print(f"  {e}")


if __name__ == '__main__':
    main()
