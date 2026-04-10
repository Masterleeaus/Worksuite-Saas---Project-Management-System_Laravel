<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('appointments')) {
            Schema::table('appointments', function (Blueprint $table) {
                if (Schema::hasColumn('appointments', 'assigned_to')) {
                    $table->index(['assigned_to'], 'appointments_assigned_to_index');
                }
                if (Schema::hasColumn('appointments', 'assignment_status')) {
                    $table->index(['assignment_status'], 'appointments_assignment_status_index');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('appointments')) {
            Schema::table('appointments', function (Blueprint $table) {
                try { $table->dropIndex('appointments_assigned_to_index'); } catch (\Throwable $e) {}
                try { $table->dropIndex('appointments_assignment_status_index'); } catch (\Throwable $e) {}
            });
        }
    }
};
