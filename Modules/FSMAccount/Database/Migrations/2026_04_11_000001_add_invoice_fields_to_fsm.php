<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// fieldservice_account: link invoices/payments to FSM orders
return new class extends Migration {
    public function up(): void
    {
        // Add invoice linkage to fsm_orders
        if (Schema::hasTable('fsm_orders')) {
            $needsInvoiced = ! Schema::hasColumn('fsm_orders', 'invoiced');
            $needsInvoiceTotal = ! Schema::hasColumn('fsm_orders', 'invoice_total');

            if ($needsInvoiced || $needsInvoiceTotal) {
                Schema::table('fsm_orders', function (Blueprint $table) use ($needsInvoiced, $needsInvoiceTotal) {
                    if ($needsInvoiced) {
                        $table->boolean('invoiced')->default(false);
                    }
                    if ($needsInvoiceTotal) {
                        $table->decimal('invoice_total', 15, 2)->nullable();
                    }
                });
            }
        }

        // Add is_invoiceable to fsm_stages
        if (Schema::hasTable('fsm_stages') && ! Schema::hasColumn('fsm_stages', 'is_invoiceable')) {
            Schema::table('fsm_stages', function (Blueprint $table) {
                $table->boolean('is_invoiceable')->default(false);
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
