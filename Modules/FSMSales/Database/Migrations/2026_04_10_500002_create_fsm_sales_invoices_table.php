<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * FSM Sales Invoices (accounts-receivable, client-facing invoices raised from FSM Orders).
 * These sit alongside the existing Accountings module tables and can optionally
 * post journal entries into acc_journalh / acc_journald.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('fsm_sales_invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();

            // Human-readable reference: INV-00001
            $table->string('number', 64)->unique();

            // Client (user)
            $table->unsignedBigInteger('client_id')->nullable()->index();

            // Optionally tied to a service agreement
            $table->unsignedBigInteger('agreement_id')->nullable()->index();

            $table->date('invoice_date');
            $table->date('due_date')->nullable();

            $table->decimal('subtotal', 16, 2)->default(0);
            $table->decimal('tax_total', 16, 2)->default(0);
            $table->decimal('total', 16, 2)->default(0);
            $table->decimal('amount_paid', 16, 2)->default(0);

            // draft | sent | paid | overdue | void
            $table->string('status', 30)->default('draft')->index();

            // Billing schedule for recurring invoices: per_visit | monthly | quarterly | annual
            $table->string('billing_schedule', 30)->nullable();

            $table->text('notes')->nullable();

            // Link to an acc_journalh entry (optional Accountings integration)
            $table->unsignedInteger('journal_id')->nullable()->index();

            $table->timestamps();

            $table->index(['company_id', 'client_id']);
            $table->index(['company_id', 'status']);
        });

        // Pivot: invoices ↔ fsm orders (one invoice may cover several orders)
        Schema::create('fsm_sales_invoice_order', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fsm_sales_invoice_id')->index();
            $table->unsignedBigInteger('fsm_order_id')->index();

            $table->foreign('fsm_sales_invoice_id')->references('id')->on('fsm_sales_invoices')->cascadeOnDelete();
            $table->foreign('fsm_order_id')->references('id')->on('fsm_orders')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_sales_invoice_order');
        Schema::dropIfExists('fsm_sales_invoices');
    }
};
