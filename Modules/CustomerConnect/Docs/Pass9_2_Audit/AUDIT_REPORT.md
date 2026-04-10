# Titan Connect / CustomerConnect — Full Migration & Worksuite Integration Audit

Date: 2026-02-02 21:46:37

## Findings (critical)

1) **Duplicate table-create migrations (fatal)**
- The same tables were being created in **three different timestamp sets**:
  - `customerconnect_message_intents`
  - `customerconnect_ai_suggestions`
  - `customerconnect_message_media`
- This causes: `SQLSTATE[42S01] Table already exists`

**Fix applied:** kept the earliest `2026_02_02_1539370x_*` set and removed later duplicates:
- Removed:
  - 2026_02_02_15412601/2/3_*
  - 2026_02_03_000001/2/3_*

2) **Permissions FK violation (fatal in your DB)**
- Your `permissions` table enforces `module_id` FK → `modules.id`.
- The module registration migration inserted permissions **without `module_id`**, causing:
  - `SQLSTATE[23000] ... permissions_module_id_foreign`

**Fix applied:** module registration migration now:
- Creates/fetches the module row as `$module`
- Sets `permissions.module_id = $module->id` when the column exists

## Verification checklist (what to test)

- `php artisan migrate` completes with no failures
- Super Admin → Packages → Edit package:
  - `customerconnect` appears
  - label is human (requires core `resources/lang/en/modules.php` entry)
- Assign package to company:
  - CustomerConnect/Titan Connect appears in sidebar
- Remove module from package:
  - menu disappears
- Permissions:
  - user with `view_customerconnect = none` does not see menu

## Notes
- This audit does **not** change your existing DB records.
- Removing duplicate migration files is safe because migrations are tracked by filename in the `migrations` table; duplicates must not ship in the module for fresh installs.

