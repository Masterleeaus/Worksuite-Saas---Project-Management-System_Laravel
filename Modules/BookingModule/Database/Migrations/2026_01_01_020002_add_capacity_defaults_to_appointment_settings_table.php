<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('appointment_settings')) {
            return;
        }

        Schema::table('appointment_settings', function (Blueprint $table) {

            if (!Schema::hasColumn('appointment_settings', 'default_max_per_day')) {
                $table->unsignedInteger('default_max_per_day')->nullable();
            }

            if (!Schema::hasColumn('appointment_settings', 'default_max_per_slot')) {
                $table->unsignedInteger('default_max_per_slot')->nullable();
            }

            if (!Schema::hasColumn('appointment_settings', 'enforce_conflicts')) {
                $table->boolean('enforce_conflicts')->default(true);
            }

            if (!Schema::hasColumn('appointment_settings', 'count_pending_too')) {
                $table->boolean('count_pending_too')->default(false);
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('appointment_settings')) {
            return;
        }

        Schema::table('appointment_settings', function (Blueprint $table) {
            foreach ([
                'count_pending_too',
                'enforce_conflicts',
                'default_max_per_slot',
                'default_max_per_day',
            ] as $column) {
                if (Schema::hasColumn('appointment_settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
