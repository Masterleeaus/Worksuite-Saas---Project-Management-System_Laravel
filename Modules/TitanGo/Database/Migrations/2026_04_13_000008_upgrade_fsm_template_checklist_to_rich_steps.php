<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * 2026_04_13_000008_upgrade_fsm_template_checklist_to_rich_steps
 *
 * Converts the `fsm_templates.checklist` column from a JSON array of
 * plain strings to a JSON array of rich step objects:
 *
 *   Before: ["Check equipment", "Clean surface"]
 *   After:  [
 *     {"instruction":"Check equipment","photo_required":false,"signature_required":false,"is_required":true},
 *     ...
 *   ]
 *
 * Existing rows are migrated in-place; the rollback converts back to the
 * plain-string format so no data is lost.
 *
 * NOTE: This migration is safe to run on an empty `fsm_templates` table.
 */
return new class extends Migration
{
    public function up(): void
    {
        $rows = DB::table('fsm_templates')->whereNotNull('checklist')->get(['id', 'checklist']);

        foreach ($rows as $row) {
            $items = json_decode($row->checklist, true);
            if (!is_array($items) || empty($items)) {
                continue;
            }

            // Already migrated (first item is an object, not a string)
            if (is_array($items[0])) {
                continue;
            }

            $rich = array_values(array_map(function (string $instruction): array {
                return [
                    'instruction'        => trim($instruction),
                    'photo_required'     => false,
                    'signature_required' => false,
                    'is_required'        => true,
                ];
            }, array_filter($items, fn($i) => is_string($i))));

            DB::table('fsm_templates')
                ->where('id', $row->id)
                ->update(['checklist' => json_encode($rich)]);
        }
    }

    public function down(): void
    {
        $rows = DB::table('fsm_templates')->whereNotNull('checklist')->get(['id', 'checklist']);

        foreach ($rows as $row) {
            $items = json_decode($row->checklist, true);
            if (!is_array($items) || empty($items)) {
                continue;
            }

            // Already plain strings
            if (is_string($items[0])) {
                continue;
            }

            $plain = array_values(array_map(fn(array $step): string => $step['instruction'] ?? '', $items));

            DB::table('fsm_templates')
                ->where('id', $row->id)
                ->update(['checklist' => json_encode($plain)]);
        }
    }
};
