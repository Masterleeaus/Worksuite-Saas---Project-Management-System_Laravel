<?php

use Illuminate\Database\Migrations\Migration;

/**
 * Compatibility stub: documents that the `inspections` and `inspection_items` tables
 * created by earlier Inspection module migrations are SUPERSEDED by the QualityControl
 * module's `qc_records` and `qc_record_items` tables.
 *
 * Background
 * ----------
 * Migrations 2026_04_10_000001 and 2026_04_10_000002 created standalone `inspections`
 * and `inspection_items` tables for an early draft of the Inspection-as-standalone-owner
 * design. That design was abandoned in favour of the QualityControl canonical ownership
 * model (see Modules/Inspection/manifests/lifecycle.php and Modules/QualityControl/manifests/lifecycle.php).
 *
 * The canonical QC data layer:
 *   - Inspection runs   → qc_records          (managed by QC)
 *   - Inspection items  → qc_record_items      (managed by QC)
 *   - Schedules         → inspection_schedules (managed by QC)
 *   - Templates         → inspection_templates (managed by QC)
 *
 * What this stub does
 * -------------------
 * This migration is intentionally a NO-OP. It does not drop, alter, or move any data.
 * Its sole purpose is to be recorded in the `migrations` table so that:
 *   1. The retirement is tracked in version control alongside the schema.
 *   2. The migration runner never re-runs the original creation migrations.
 *   3. Future contributors can see in `php artisan migrate:status` that these tables
 *      have been marked as superseded.
 *
 * Rules
 * -----
 * - Do NOT drop `inspections` or `inspection_items` here (no destructive changes).
 * - If a hard-drop is desired in a future cleanup pass, that must be a separate, explicit
 *   migration with a data-migration or emptiness check.
 */
return new class extends Migration {
    public function up(): void
    {
        // No-op. See docblock above.
        // Tables `inspections` and `inspection_items` are superseded by `qc_records`
        // and `qc_record_items`. All active code routes through QC domain actions.
    }

    public function down(): void
    {
        // No-op. Retirement cannot be reversed without a new forward migration.
    }
};
