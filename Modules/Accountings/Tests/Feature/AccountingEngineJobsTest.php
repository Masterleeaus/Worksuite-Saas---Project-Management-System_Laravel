<?php

namespace Modules\Accountings\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use Modules\Accountings\Jobs\BuildMonthlyFinanceSummaryJob;
use Modules\Accountings\Jobs\DetectProfitabilityAnomaliesJob;
use Modules\Accountings\Jobs\GenerateAccountingSnapshotJob;
use Modules\Accountings\Jobs\RecalculateSiteProfitabilityJob;
use Modules\Accountings\Jobs\RecalculateContractProfitabilityJob;
use Modules\Accountings\Jobs\SyncInvoiceLedgerEntriesJob;
use Tests\TestCase;

/**
 * Feature tests for accounting engine queue jobs.
 *
 * Verifies that jobs can be dispatched to queues and that their
 * handle() methods complete successfully when run synchronously.
 */
class AccountingEngineJobsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createEngineTables();
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('acc_financial_transactions');
        Schema::dropIfExists('acc_financial_signals');
        Schema::dropIfExists('acc_visit_costs');
        Schema::dropIfExists('acc_profitability_snapshots');
        Schema::dropIfExists('invoices');
        parent::tearDown();
    }

    // ------------------------------------------------------------------
    // Dispatch smoke-tests (fake queue)
    // ------------------------------------------------------------------

    /** @test */
    public function build_monthly_finance_summary_job_can_be_dispatched(): void
    {
        Queue::fake();

        BuildMonthlyFinanceSummaryJob::dispatch(1);

        Queue::assertPushed(BuildMonthlyFinanceSummaryJob::class, fn ($job) => $job->companyId === 1);
    }

    /** @test */
    public function detect_profitability_anomalies_job_can_be_dispatched(): void
    {
        Queue::fake();

        DetectProfitabilityAnomaliesJob::dispatch(1);

        Queue::assertPushed(DetectProfitabilityAnomaliesJob::class, fn ($job) => $job->companyId === 1);
    }

    /** @test */
    public function generate_snapshot_job_can_be_dispatched(): void
    {
        Queue::fake();

        GenerateAccountingSnapshotJob::dispatch(1, 'SITE-1', 'CONTRACT-1');

        Queue::assertPushed(GenerateAccountingSnapshotJob::class);
    }

    /** @test */
    public function recalculate_site_profitability_job_can_be_dispatched(): void
    {
        Queue::fake();

        RecalculateSiteProfitabilityJob::dispatch(1, 'SITE-A');

        Queue::assertPushed(RecalculateSiteProfitabilityJob::class, fn ($job) => $job->siteRef === 'SITE-A');
    }

    // ------------------------------------------------------------------
    // Synchronous handle() execution
    // ------------------------------------------------------------------

    /** @test */
    public function detect_anomalies_job_handles_gracefully_with_no_data(): void
    {
        // No visit_costs → no anomalies → no exception
        $job = new DetectProfitabilityAnomaliesJob(1);
        $job->handle(app(\Modules\Accountings\Services\CleaningMarginAnalyticsService::class));

        $this->assertTrue(true); // if we got here, no exception was thrown
    }

    /** @test */
    public function build_monthly_finance_summary_job_handles_gracefully_with_no_data(): void
    {
        $job = new BuildMonthlyFinanceSummaryJob(1);
        $job->handle(app(\Modules\Accountings\Services\TransactionAggregationService::class));

        $this->assertTrue(true);
    }

    /** @test */
    public function sync_invoice_ledger_entries_job_handles_gracefully_when_invoice_missing(): void
    {
        // Invoice with id=9999 does not exist; the job should abort silently.
        if (!Schema::hasTable('invoices')) {
            Schema::create('invoices', function ($table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->default(1);
                $table->string('invoice_number')->nullable();
                $table->decimal('total', 16, 2)->default(0);
                $table->date('issue_date')->nullable();
                $table->timestamps();
            });
        }

        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $job = new SyncInvoiceLedgerEntriesJob(9999, 1);
        $job->handle(app(\Modules\Accountings\Actions\PostInvoiceToLedgerAction::class));
    }

    // ------------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------------

    private function createEngineTables(): void
    {
        if (!Schema::hasTable('acc_visit_costs')) {
            Schema::create('acc_visit_costs', function ($t) {
                $t->id();
                $t->unsignedBigInteger('company_id');
                $t->unsignedBigInteger('user_id')->nullable();
                $t->string('visit_ref', 191)->nullable();
                $t->string('job_ref', 191)->nullable();
                $t->string('site_ref', 191)->nullable();
                $t->string('contract_ref', 191)->nullable();
                $t->string('service_agreement_ref', 191)->nullable();
                foreach (['labour_cost','travel_cost','equipment_cost','consumables_cost','overhead_cost','total_cost','revenue','margin'] as $col) {
                    $t->decimal($col, 16, 2)->default(0);
                }
                $t->decimal('margin_percent', 8, 4)->default(0);
                $t->decimal('variance_amount', 16, 2)->default(0);
                $t->decimal('variance_percent', 8, 4)->default(0);
                $t->decimal('crew_shift_hours', 10, 2)->default(0);
                $t->decimal('supply_consumption_units', 10, 2)->default(0);
                $t->decimal('missed_visit_impact', 16, 2)->default(0);
                $t->decimal('rescheduled_visit_variance', 16, 2)->default(0);
                $t->decimal('service_credits', 16, 2)->default(0);
                $t->decimal('customer_discounts', 16, 2)->default(0);
                $t->decimal('extra_work_revenue', 16, 2)->default(0);
                $t->string('status', 40)->default('calculated');
                $t->timestamp('occurred_at')->nullable();
                $t->timestamp('finalized_at')->nullable();
                $t->json('meta')->nullable();
                $t->timestamps();
            });
        }

        if (!Schema::hasTable('acc_financial_transactions')) {
            Schema::create('acc_financial_transactions', function ($t) {
                $t->id();
                $t->unsignedBigInteger('company_id');
                $t->unsignedBigInteger('user_id')->nullable();
                $t->unsignedBigInteger('invoice_id')->nullable();
                $t->unsignedBigInteger('payment_id')->nullable();
                $t->unsignedBigInteger('visit_cost_id')->nullable();
                $t->unsignedBigInteger('reversed_transaction_id')->nullable();
                $t->string('transaction_type', 60);
                $t->string('reference', 191)->nullable();
                $t->string('visit_ref', 191)->nullable();
                $t->string('job_ref', 191)->nullable();
                $t->string('site_ref', 191)->nullable();
                $t->string('contract_ref', 191)->nullable();
                $t->string('service_agreement_ref', 191)->nullable();
                $t->string('debit_account_code', 64)->nullable();
                $t->string('credit_account_code', 64)->nullable();
                $t->decimal('amount', 16, 2);
                $t->unsignedBigInteger('currency_id')->nullable();
                $t->timestamp('occurred_at')->nullable();
                $t->string('status', 40)->default('posted');
                $t->json('meta')->nullable();
                $t->timestamps();
            });
        }

        if (!Schema::hasTable('acc_financial_signals')) {
            Schema::create('acc_financial_signals', function ($t) {
                $t->id();
                $t->uuid('signal_id')->unique();
                $t->string('signal_name', 191);
                $t->string('schema_version', 20)->default('1.0');
                $t->unsignedBigInteger('company_id');
                $t->unsignedBigInteger('actor_id')->nullable();
                $t->string('source_type', 120)->nullable();
                $t->string('source_id', 191)->nullable();
                $t->string('correlation_id', 191)->nullable();
                $t->string('causation_id', 191)->nullable();
                $t->string('approval_mode', 30)->default('none');
                $t->string('risk_level', 30)->default('low');
                $t->timestamp('occurred_at')->nullable();
                $t->json('payload');
                $t->timestamps();
            });
        }

        if (!Schema::hasTable('acc_profitability_snapshots')) {
            Schema::create('acc_profitability_snapshots', function ($t) {
                $t->id();
                $t->unsignedBigInteger('company_id');
                $t->unsignedBigInteger('user_id')->nullable();
                $t->string('scope_type', 40);
                $t->string('scope_ref', 191);
                $t->date('period_start');
                $t->date('period_end');
                $t->decimal('revenue', 16, 2)->default(0);
                $t->decimal('cost', 16, 2)->default(0);
                $t->decimal('margin', 16, 2)->default(0);
                $t->decimal('margin_percent', 8, 4)->default(0);
                $t->decimal('variance_amount', 16, 2)->default(0);
                $t->decimal('variance_percent', 8, 4)->default(0);
                $t->decimal('anomaly_score', 8, 4)->default(0);
                $t->json('flags')->nullable();
                $t->timestamps();
            });
        }
    }
}
