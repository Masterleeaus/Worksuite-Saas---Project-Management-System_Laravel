<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Line items on an FSM Sales Invoice.
 * Covers: service charge, timesheet hours, billable stock consumables, equipment.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('fsm_sales_invoice_lines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('fsm_sales_invoice_id')->index();

            // Optional back-reference to the order that generated this line
            $table->unsignedBigInteger('fsm_order_id')->nullable()->index();

            // Line type: service | timesheet | stock | equipment | other
            $table->string('line_type', 30)->default('service');

            $table->string('description', 255)->nullable();

            $table->decimal('qty', 12, 4)->default(1);
            $table->decimal('unit_price', 16, 2)->default(0);
            $table->decimal('tax_rate', 6, 4)->default(0);   // e.g. 0.1000 = 10%
            $table->decimal('line_subtotal', 16, 2)->default(0);
            $table->decimal('line_tax', 16, 2)->default(0);
            $table->decimal('line_total', 16, 2)->default(0);

            // Optional stock line reference (FSMStock integration)
            $table->unsignedBigInteger('stock_line_id')->nullable()->index();

            $table->timestamps();

            $table->foreign('fsm_sales_invoice_id')
                ->references('id')->on('fsm_sales_invoices')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_sales_invoice_lines');
    }
};
