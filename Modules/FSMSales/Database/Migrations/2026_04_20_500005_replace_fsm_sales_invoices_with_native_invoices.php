<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('invoices') && !Schema::hasColumn('invoices', 'fsm_order_id')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->unsignedBigInteger('fsm_order_id')->nullable()->after('order_id');

                if (Schema::hasTable('fsm_orders')) {
                    $table->foreign('fsm_order_id')
                        ->references('id')
                        ->on('fsm_orders')
                        ->nullOnDelete();
                }
            });
        }

        if (Schema::hasTable('invoice_items') && !Schema::hasColumn('invoice_items', 'fsm_order_id')) {
            Schema::table('invoice_items', function (Blueprint $table) {
                $table->unsignedBigInteger('fsm_order_id')->nullable()->after('invoice_id');

                if (Schema::hasTable('fsm_orders')) {
                    $table->foreign('fsm_order_id')
                        ->references('id')
                        ->on('fsm_orders')
                        ->nullOnDelete();
                }
            });
        }

        if (!Schema::hasTable('fsm_order_invoice')) {
            Schema::create('fsm_order_invoice', function (Blueprint $table) {
                $table->unsignedBigInteger('fsm_order_id');
                $table->unsignedInteger('invoice_id');
                $table->primary(['fsm_order_id', 'invoice_id']);

                if (Schema::hasTable('fsm_orders')) {
                    $table->foreign('fsm_order_id')->references('id')->on('fsm_orders')->cascadeOnDelete();
                }
                if (Schema::hasTable('invoices')) {
                    $table->foreign('invoice_id')->references('id')->on('invoices')->cascadeOnDelete();
                }
            });
        }

        Schema::dropIfExists('fsm_sales_invoice_lines');
        Schema::dropIfExists('fsm_recurring_invoices');
        Schema::dropIfExists('fsm_sales_invoice_order');
        Schema::dropIfExists('fsm_sales_invoices');
    }

    public function down(): void
    {
        if (Schema::hasTable('invoice_items') && Schema::hasColumn('invoice_items', 'fsm_order_id')) {
            Schema::table('invoice_items', function (Blueprint $table) {
                $table->dropForeign(['fsm_order_id']);
                $table->dropColumn('fsm_order_id');
            });
        }

        if (Schema::hasTable('invoices') && Schema::hasColumn('invoices', 'fsm_order_id')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->dropForeign(['fsm_order_id']);
                $table->dropColumn('fsm_order_id');
            });
        }
    }
};

