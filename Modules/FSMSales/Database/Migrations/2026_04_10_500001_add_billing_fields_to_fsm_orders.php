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
        Schema::table('fsm_orders', function (Blueprint $table) {
            $table->string('billing_policy', 30)->default('manual')->after('description');
            $table->decimal('billing_amount', 16, 2)->nullable()->after('billing_policy');
            $table->decimal('hourly_rate', 10, 2)->nullable()->after('billing_amount');
            $table->boolean('is_invoiced')->default(false)->after('hourly_rate');
        });
    }

    public function down(): void
    {
        Schema::table('fsm_orders', function (Blueprint $table) {
            $table->dropColumn(['billing_policy', 'billing_amount', 'hourly_rate', 'is_invoiced']);
        });
    }
};
