<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// fieldservice_account: link invoices/payments to FSM orders
return new class extends Migration {
    public function up(): void
    {
        // Add invoice linkage to fsm_orders
        if (Schema::hasTable('fsm_orders') && ! Schema::hasColumn('fsm_orders', 'invoiced')) {
            Schema::table('fsm_orders', function (Blueprint $table) {
                $table->boolean('invoiced')->default(false)->after('duration');
                $table->decimal('invoice_total', 15, 2)->nullable()->after('invoiced');
            });
        }

        // Add is_invoiceable to fsm_stages
        if (Schema::hasTable('fsm_stages') && ! Schema::hasColumn('fsm_stages', 'is_invoiceable')) {
            Schema::table('fsm_stages', function (Blueprint $table) {
                $table->boolean('is_invoiceable')->default(false)->after('is_closed');
            });
        }

        // Pivot: fsm_orders ↔ invoices
        if (! Schema::hasTable('fsm_order_invoice')) {
            Schema::create('fsm_order_invoice', function (Blueprint $table) {
                $table->unsignedBigInteger('fsm_order_id');
                $table->unsignedBigInteger('invoice_id');
                $table->primary(['fsm_order_id', 'invoice_id']);
                $table->foreign('fsm_order_id')->references('id')->on('fsm_orders')->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_order_invoice');

        if (Schema::hasTable('fsm_orders')) {
            Schema::table('fsm_orders', function (Blueprint $table) {
                if (Schema::hasColumn('fsm_orders', 'invoiced')) $table->dropColumn('invoiced');
                if (Schema::hasColumn('fsm_orders', 'invoice_total')) $table->dropColumn('invoice_total');
            });
        }

        if (Schema::hasTable('fsm_stages') && Schema::hasColumn('fsm_stages', 'is_invoiceable')) {
            Schema::table('fsm_stages', function (Blueprint $table) {
                $table->dropColumn('is_invoiceable');
            });
        }
    }
};
