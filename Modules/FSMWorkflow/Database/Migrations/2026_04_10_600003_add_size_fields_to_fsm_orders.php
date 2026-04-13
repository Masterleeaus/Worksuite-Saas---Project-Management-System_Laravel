<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Part 3 – Adds size/scope fields to fsm_orders.
 *
 * Adds:
 *   - size_id       FK → fsm_sizes
 *   - estimated_sqm square metres (optional)
 *   - room_count    number of rooms (used by Payroll commission calculation)
 */
return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('fsm_orders')) {
            return;
        }
        
        Schema::table('fsm_orders', function (Blueprint $table) {
            if (! Schema::hasColumn('fsm_orders', 'size_id')) {
                $table->unsignedBigInteger('size_id')->nullable()->after('stage_id')->index();
            }
            if (! Schema::hasColumn('fsm_orders', 'estimated_sqm')) {
                $table->unsignedInteger('estimated_sqm')->nullable()->after('size_id');
            }
            if (! Schema::hasColumn('fsm_orders', 'room_count')) {
                $table->unsignedInteger('room_count')->nullable()->after('estimated_sqm');
            }

            $table->foreign('size_id')->references('id')->on('fsm_sizes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('fsm_orders')) {
            return;
        }
        
        Schema::table('fsm_orders', function (Blueprint $table) {
            $table->dropForeign(['size_id']);
            $table->dropColumn(['size_id', 'estimated_sqm', 'room_count']);
        });
    }
};
