<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('acc_visit_costs')) {
            Schema::create('acc_visit_costs', function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('company_id')->index();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('visit_ref', 191)->index();
                $table->string('job_ref', 191)->nullable()->index();
                $table->string('site_ref', 191)->nullable()->index();
                $table->string('contract_ref', 191)->nullable()->index();
                $table->string('service_agreement_ref', 191)->nullable()->index();
                $table->decimal('labour_cost', 16, 2)->default(0);
                $table->decimal('travel_cost', 16, 2)->default(0);
                $table->decimal('equipment_cost', 16, 2)->default(0);
                $table->decimal('consumables_cost', 16, 2)->default(0);
                $table->decimal('overhead_cost', 16, 2)->default(0);
                $table->decimal('total_cost', 16, 2)->default(0);
                $table->decimal('revenue', 16, 2)->default(0);
                $table->decimal('margin', 16, 2)->default(0);
                $table->decimal('margin_percent', 8, 4)->default(0);
                $table->decimal('variance_amount', 16, 2)->default(0);
                $table->decimal('variance_percent', 8, 4)->default(0);
                $table->decimal('crew_shift_hours', 10, 2)->default(0);
                $table->decimal('supply_consumption_units', 10, 2)->default(0);
                $table->decimal('missed_visit_impact', 16, 2)->default(0);
                $table->decimal('rescheduled_visit_variance', 16, 2)->default(0);
                $table->decimal('service_credits', 16, 2)->default(0);
                $table->decimal('customer_discounts', 16, 2)->default(0);
                $table->decimal('extra_work_revenue', 16, 2)->default(0);
                $table->string('status', 40)->default('calculated')->index();
                $table->timestamp('occurred_at')->nullable()->index();
                $table->timestamp('finalized_at')->nullable()->index();
                $table->json('meta')->nullable();
                $table->timestamps();
                $table->unique(['company_id', 'visit_ref'], 'acc_visit_costs_company_visit_unique');
            });
        }

        if (!Schema::hasTable('acc_financial_transactions')) {
            Schema::create('acc_financial_transactions', function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('company_id')->index();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->unsignedBigInteger('invoice_id')->nullable()->index();
                $table->unsignedBigInteger('payment_id')->nullable()->index();
                $table->unsignedBigInteger('visit_cost_id')->nullable()->index();
                $table->unsignedBigInteger('reversed_transaction_id')->nullable()->index();
                $table->string('transaction_type', 60)->index();
                $table->string('reference', 191)->nullable()->index();
                $table->string('visit_ref', 191)->nullable()->index();
                $table->string('job_ref', 191)->nullable()->index();
                $table->string('site_ref', 191)->nullable()->index();
                $table->string('contract_ref', 191)->nullable()->index();
                $table->string('service_agreement_ref', 191)->nullable()->index();
                $table->string('debit_account_code', 64)->nullable()->index();
                $table->string('credit_account_code', 64)->nullable()->index();
                $table->decimal('amount', 16, 2);
                $table->unsignedBigInteger('currency_id')->nullable()->index();
                $table->timestamp('occurred_at')->nullable()->index();
                $table->string('status', 40)->default('posted')->index();
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('acc_profitability_snapshots')) {
            Schema::create('acc_profitability_snapshots', function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('company_id')->index();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('scope_type', 40)->index();
                $table->string('scope_ref', 191)->index();
                $table->date('period_start')->index();
                $table->date('period_end')->index();
                $table->decimal('revenue', 16, 2)->default(0);
                $table->decimal('cost', 16, 2)->default(0);
                $table->decimal('margin', 16, 2)->default(0);
                $table->decimal('margin_percent', 8, 4)->default(0);
                $table->decimal('variance_amount', 16, 2)->default(0);
                $table->decimal('variance_percent', 8, 4)->default(0);
                $table->decimal('anomaly_score', 8, 4)->default(0);
                $table->json('flags')->nullable();
                $table->timestamps();
                $table->index(['company_id', 'scope_type', 'scope_ref'], 'acc_profit_scope_idx');
            });
        }

        if (!Schema::hasTable('acc_accounting_periods')) {
            Schema::create('acc_accounting_periods', function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('company_id')->index();
                $table->unsignedBigInteger('closed_by')->nullable()->index();
                $table->string('period_key', 40)->index();
                $table->date('period_start')->index();
                $table->date('period_end')->index();
                $table->string('status', 30)->default('open')->index();
                $table->timestamp('closed_at')->nullable()->index();
                $table->json('meta')->nullable();
                $table->timestamps();
                $table->unique(['company_id', 'period_key'], 'acc_period_company_key_unique');
            });
        }

        if (!Schema::hasTable('acc_financial_signals')) {
            Schema::create('acc_financial_signals', function (Blueprint $table): void {
                $table->id();
                $table->uuid('signal_id')->unique();
                $table->string('signal_name', 191)->index();
                $table->string('schema_version', 20)->default('1.0');
                $table->unsignedBigInteger('company_id')->index();
                $table->unsignedBigInteger('actor_id')->nullable()->index();
                $table->string('source_type', 120)->nullable()->index();
                $table->string('source_id', 191)->nullable()->index();
                $table->string('correlation_id', 191)->nullable()->index();
                $table->string('causation_id', 191)->nullable()->index();
                $table->string('approval_mode', 30)->default('none')->index();
                $table->string('risk_level', 30)->default('low')->index();
                $table->timestamp('occurred_at')->nullable()->index();
                $table->json('payload');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('acc_financial_signals');
        Schema::dropIfExists('acc_accounting_periods');
        Schema::dropIfExists('acc_profitability_snapshots');
        Schema::dropIfExists('acc_financial_transactions');
        Schema::dropIfExists('acc_visit_costs');
    }
};
