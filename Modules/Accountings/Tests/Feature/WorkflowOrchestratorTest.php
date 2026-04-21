<?php

namespace Modules\Accountings\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use Modules\Accountings\Actions\FinalizeJobCostAction;
use Modules\Accountings\Actions\PostVisitCostAction;
use Modules\Accountings\Events\InvoiceIssued;
use Modules\Accountings\Events\JobCostFinalized;
use Modules\Accountings\Events\PaymentReceived;
use Modules\Accountings\Events\VisitCostCalculated;
use Modules\Accountings\Jobs\RecalculateContractProfitabilityJob;
use Modules\Accountings\Workflows\AccountingWorkflowOrchestrator;
use Tests\TestCase;

/**
 * Feature tests for AccountingWorkflowOrchestrator.
 *
 * Verifies that each workflow entry-point correctly delegates to the
 * appropriate action/job and emits the expected domain events.
 */
class WorkflowOrchestratorTest extends TestCase
{
    use RefreshDatabase;

    private AccountingWorkflowOrchestrator $orchestrator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createEngineTables();
        $this->orchestrator = app(AccountingWorkflowOrchestrator::class);
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('acc_financial_transactions');
        Schema::dropIfExists('acc_financial_signals');
        Schema::dropIfExists('acc_visit_costs');
        Schema::dropIfExists('acc_profitability_snapshots');
        parent::tearDown();
    }

    // ------------------------------------------------------------------
    // visitCompleted
    // ------------------------------------------------------------------

    /** @test */
    public function visit_completed_workflow_emits_visit_cost_calculated_event(): void
    {
        Event::fake([VisitCostCalculated::class, JobCostFinalized::class]);

        $this->orchestrator->visitCompleted([
            'company_id'  => 1,
            'visit_ref'   => 'V-FLOW-001',
            'labour_cost' => 80,
            'revenue'     => 200,
        ]);

        Event::assertDispatched(VisitCostCalculated::class);
        Event::assertDispatched(JobCostFinalized::class);
    }

    /** @test */
    public function visit_completed_sets_status_to_finalized(): void
    {
        $this->orchestrator->visitCompleted([
            'company_id'  => 1,
            'visit_ref'   => 'V-FLOW-002',
            'labour_cost' => 50,
            'revenue'     => 120,
        ]);

        $status = \Illuminate\Support\Facades\DB::table('acc_visit_costs')
            ->where('visit_ref', 'V-FLOW-002')
            ->value('status');

        $this->assertSame('finalized', $status);
    }

    // ------------------------------------------------------------------
    // contractCycleClosed
    // ------------------------------------------------------------------

    /** @test */
    public function contract_cycle_closed_dispatches_recalculate_job(): void
    {
        Queue::fake();

        $this->orchestrator->contractCycleClosed(1, 'CONTRACT-ABC');

        Queue::assertPushed(RecalculateContractProfitabilityJob::class, function ($job) {
            return $job->companyId === 1 && $job->contractRef === 'CONTRACT-ABC';
        });
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
                $t->string('visit_ref', 191);
                $t->string('job_ref', 191)->nullable();
                $t->string('site_ref', 191)->nullable();
                $t->string('contract_ref', 191)->nullable();
                $t->string('service_agreement_ref', 191)->nullable();
                $t->decimal('labour_cost', 16, 2)->default(0);
                $t->decimal('travel_cost', 16, 2)->default(0);
                $t->decimal('equipment_cost', 16, 2)->default(0);
                $t->decimal('consumables_cost', 16, 2)->default(0);
                $t->decimal('overhead_cost', 16, 2)->default(0);
                $t->decimal('total_cost', 16, 2)->default(0);
                $t->decimal('revenue', 16, 2)->default(0);
                $t->decimal('margin', 16, 2)->default(0);
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
                $t->unique(['company_id', 'visit_ref']);
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
