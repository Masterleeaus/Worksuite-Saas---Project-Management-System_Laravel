<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Seed automation defaults into appointment_settings.
 *
 * These are global (company_id = NULL, workspace = NULL) defaults that serve as
 * the fallback when no per-company DB override has been saved.  Per-company rows
 * added via the settings UI take precedence over these rows at query time.
 *
 * Safe to run on fresh installs and existing databases (idempotent).
 */
return new class extends Migration
{
    private const DEFAULTS = [
        // Reminder lead times: comma-separated minutes, or JSON array
        'automation.reminders.lead_time_minutes' => '1440,120',

        // Tolerance window in minutes for matching schedule start times
        'automation.reminders.tolerance_minutes' => '5',

        // Whether to send a reminder immediately after assignment
        'automation.reminders.after_assignment' => '1',

        // Whether to send a reminder when a schedule is rescheduled
        'automation.reminders.after_reschedule' => '1',

        // Whether to auto-generate invoice on booking completion
        'automation.invoice.auto_generate' => '1',

        // Whether to sync dispatch board cache after booking completion
        'automation.dispatch.sync_on_complete' => '1',
    ];

    public function up(): void
    {
        if (!Schema::hasTable('appointment_settings')) {
            return;
        }

        foreach (self::DEFAULTS as $key => $value) {
            // Only insert if no row with this key exists (any company or global).
            $exists = DB::table('appointment_settings')
                ->where('key', $key)
                ->exists();

            if (!$exists) {
                DB::table('appointment_settings')->insert([
                    'company_id'  => null,
                    'workspace'   => null,
                    'created_by'  => null,
                    'key'         => $key,
                    'value'       => $value,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('appointment_settings')) {
            return;
        }

        DB::table('appointment_settings')
            ->whereIn('key', array_keys(self::DEFAULTS))
            ->whereNull('company_id')
            ->whereNull('workspace')
            ->delete();
    }
};
