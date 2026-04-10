<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('schedules')) {
            Schema::table('schedules', function (Blueprint $table) {
                if (!Schema::hasColumn('schedules', 'assigned_to')) {
                    // Keep existing user_id for backward compatibility, but prefer assigned_to going forward.
                    $table->unsignedBigInteger('assigned_to')->nullable()->after('user_id')->index();
                }
                if (!Schema::hasColumn('schedules', 'assigned_by')) {
                    $table->unsignedBigInteger('assigned_by')->nullable()->after('assigned_to')->index();
                }
                if (!Schema::hasColumn('schedules', 'assigned_at')) {
                    $table->timestamp('assigned_at')->nullable()->after('assigned_by');
                }
                if (!Schema::hasColumn('schedules', 'assignment_status')) {
                    $table->string('assignment_status', 30)->default('unassigned')->after('assigned_at')->index();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('schedules')) {
            Schema::table('schedules', function (Blueprint $table) {
                // Best-effort reversals.
                try { $table->dropColumn(['assigned_to', 'assigned_by', 'assigned_at', 'assignment_status']); } catch (\Throwable $e) {}
            });
        }
    }
};
