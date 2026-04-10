#!/usr/bin/env python3
"""
Normalize module directory trees.

Each module should use ONLY the canonical uppercase structure:
  Config/  Database/Migrations/  Resources/  Routes/

If a lowercase duplicate exists (config/, database/, resources/, routes/),
check if it contains any files not in the uppercase version, merge them,
then remove the lowercase duplicate.
"""

import os
import shutil
from pathlib import Path

BASE = Path('/home/user/Worksuite-Saas---Project-Management-System_Laravel/Modules')

# Map lowercase -> uppercase canonical name
DIR_MAP = {
    'config': 'Config',
    'database': 'Database',
    'resources': 'Resources',
    'routes': 'Routes',
}

STATS = {
    'modules_with_duplicates': 0,
    'files_merged': 0,
    'files_already_exist': 0,
    'dirs_removed': 0,
    'modules_cleaned': [],
}


def merge_and_remove(src_dir: Path, dst_dir: Path, module_name: str):
    """Merge files from src_dir into dst_dir, then remove src_dir."""
    if not src_dir.exists():
        return

    dst_dir.mkdir(parents=True, exist_ok=True)

    for src_file in src_dir.rglob('*'):
        if src_file.is_dir():
            continue

        # Compute relative path within src_dir
        rel = src_file.relative_to(src_dir)
        dst_file = dst_dir / rel

        if dst_file.exists():
            STATS['files_already_exist'] += 1
            # The canonical (uppercase) version wins; skip
        else:
            dst_file.parent.mkdir(parents=True, exist_ok=True)
            shutil.copy2(src_file, dst_file)
            STATS['files_merged'] += 1
            print(f"  Merged: {module_name}/{dst_dir.name}/{rel}")

    # Remove the source directory after merging
    shutil.rmtree(src_dir)
    STATS['dirs_removed'] += 1


def process_module(module_dir: Path):
    has_duplicates = False

    for lower_name, upper_name in DIR_MAP.items():
        lower_dir = module_dir / lower_name
        upper_dir = module_dir / upper_name

        if not lower_dir.exists():
            continue

        has_duplicates = True

        if upper_dir.exists():
            # Both exist: merge lowercase into uppercase, remove lowercase
            merge_and_remove(lower_dir, upper_dir, module_dir.name)
        else:
            # Only lowercase exists: copy to uppercase, remove lowercase
            merge_and_remove(lower_dir, upper_dir, module_dir.name)
            print(f"  Promoted: {module_dir.name}/{lower_name} -> {upper_name}")

    return has_duplicates


def main():
    for module_dir in sorted(BASE.iterdir()):
        if not module_dir.is_dir():
            continue
        if module_dir.name == 'modules':  # Skip the Odoo modules dir
            continue

        had = process_module(module_dir)
        if had:
            STATS['modules_with_duplicates'] += 1
            STATS['modules_cleaned'].append(module_dir.name)

    print(f"\nResults:")
    print(f"  Modules with duplicate trees: {STATS['modules_with_duplicates']}")
    print(f"  Files merged from lowercase: {STATS['files_merged']}")
    print(f"  Files already in uppercase (skipped): {STATS['files_already_exist']}")
    print(f"  Directories removed: {STATS['dirs_removed']}")
    print(f"\nModules cleaned:")
    for m in STATS['modules_cleaned']:
        print(f"  {m}")


if __name__ == '__main__':
    main()
