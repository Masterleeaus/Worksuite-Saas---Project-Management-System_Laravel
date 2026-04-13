<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add billing fields to fsm_orders:
 *   billing_policy  – manual | on_completion | on_timesheet
 *   billing_amount  – fixed charge override (null = use template / timesheet)
 *   hourly_rate     – rate used for on_timesheet billing
 *   is_invoiced     – quick flag: at least one invoice has been raised
 */
return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('fsm_orders')) {
            return;
        }
        
        Schema::table('fsm_orders', function (Blueprint $table) {
            if (! Schema::hasColumn('fsm_orders', 'billing_policy')) {
                $table->string('billing_policy', 30)->default('manual')->after('description')->nullable();
            }
            if (! Schema::hasColumn('fsm_orders', 'billing_amount')) {
                $table->decimal('billing_amount', 16, 2)->nullable()->after('billing_policy');
            }
            if (! Schema::hasColumn('fsm_orders', 'hourly_rate')) {
                $table->decimal('hourly_rate', 10, 2)->nullable()->after('billing_amount');
            }
            if (! Schema::hasColumn('fsm_orders', 'is_invoiced')) {
                $table->boolean('is_invoiced')->default(false)->after('hourly_rate')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('fsm_orders')) {
            return;
        }
        
        Schema::table('fsm_orders', function (Blueprint $table) {
            $table->dropColumn(['billing_policy', 'billing_amount', 'hourly_rate', 'is_invoiced']);
        });
    }
};
