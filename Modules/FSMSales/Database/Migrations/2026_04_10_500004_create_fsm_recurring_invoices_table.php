<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Recurring invoice queue – tracks scheduled invoices generated from service agreements.
 * Status lifecycle: draft → sent → paid → overdue
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('fsm_recurring_invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();

            // Source agreement (requires FSMServiceAgreement)
            $table->unsignedBigInteger('agreement_id')->nullable()->index();

            // The concrete invoice once reviewed and sent
            $table->unsignedBigInteger('fsm_sales_invoice_id')->nullable()->index();

            $table->unsignedBigInteger('client_id')->nullable()->index();

            // Billing schedule: per_visit | monthly | quarterly | annual
            $table->string('billing_schedule', 30)->default('monthly');

            // Period this recurring invoice covers
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();

            $table->decimal('amount', 16, 2)->default(0);

            // draft | sent | paid | overdue
            $table->string('status', 30)->default('draft')->index();

            // When the system triggered this recurring entry
            $table->date('due_date')->nullable();

            // Overdue notification sent flag
            $table->boolean('overdue_notified')->default(false);

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['company_id', 'status']);
            $table->index(['company_id', 'agreement_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_recurring_invoices');
    }
};
