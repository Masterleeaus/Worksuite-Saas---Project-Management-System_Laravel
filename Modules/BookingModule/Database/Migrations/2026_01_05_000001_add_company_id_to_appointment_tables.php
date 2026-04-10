<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add tenant scoping key to Appointment module tables.
     * Idempotent and safe to re-run.
     */
    public function up(): void
    {
        $tables = ["questions", "appointments", "schedules", "appointment_callbacks", "appointment_assignments", "appointment_settings", "schedule_assignments", "appointment_staff_capacities", "appointment_notification_preferences", "appointment_notification_logs"];
        foreach ($tables as $table) {
            if (!Schema::hasTable($table)) {
                continue;
            }
            if (Schema::hasColumn($table, 'company_id')) {
                continue;
            }
            Schema::table($table, function (Blueprint $table) {
                // Nullable to avoid breaking existing data; backfill can be done safely later.
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
    }

    public function down(): void
    {
        $tables = ["questions", "appointments", "schedules", "appointment_callbacks", "appointment_assignments", "appointment_settings", "schedule_assignments", "appointment_staff_capacities", "appointment_notification_preferences", "appointment_notification_logs"];
        foreach ($tables as $table) {
            if (!Schema::hasTable($table) || !Schema::hasColumn($table, 'company_id')) {
                continue;
            }
            Schema::table($table, function (Blueprint $table) {
                $table->dropIndex(['company_id']);
                $table->dropColumn('company_id');
            });
        }
    }
};
