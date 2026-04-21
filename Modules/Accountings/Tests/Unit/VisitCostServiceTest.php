<?php

namespace Modules\Accountings\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Modules\Accountings\Entities\VisitCost;
use Modules\Accountings\Events\VisitCostCalculated;
use Modules\Accountings\Services\AccountingSignalService;
use Modules\Accountings\Services\VisitCostService;
use Tests\TestCase;

/**
 * Unit tests for VisitCostService::calculateAndStore().
 *
 * These tests run against an in-memory SQLite database and
 * verify cost calculation, persistence, and event emission.
 */
class VisitCostServiceTest extends TestCase
{
    use RefreshDatabase;

    private VisitCostService $service;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure the engine tables exist for the in-memory DB.
        if (!Schema::hasTable('acc_visit_costs')) {
            Schema::create('acc_visit_costs', function ($table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->index();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('visit_ref', 191)->index();
                $table->string('job_ref', 191)->nullable();
                $table->string('site_ref', 191)->nullable();
                $table->string('contract_ref', 191)->nullable();
                $table->string('service_agreement_ref', 191)->nullable();
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
                $table->string('status', 40)->default('calculated');
                $table->timestamp('occurred_at')->nullable();
                $table->timestamp('finalized_at')->nullable();
                $table->json('meta')->nullable();
                $table->timestamps();
                $table->unique(['company_id', 'visit_ref']);
            });
        }

        if (!Schema::hasTable('acc_financial_signals')) {
            Schema::create('acc_financial_signals', function ($table) {
                $table->id();
                $table->uuid('signal_id')->unique();
                $table->string('signal_name', 191);
                $table->string('schema_version', 20)->default('1.0');
                $table->unsignedBigInteger('company_id');
                $table->unsignedBigInteger('actor_id')->nullable();
                $table->string('source_type', 120)->nullable();
                $table->string('source_id', 191)->nullable();
                $table->string('correlation_id', 191)->nullable();
                $table->string('causation_id', 191)->nullable();
                $table->string('approval_mode', 30)->default('none');
                $table->string('risk_level', 30)->default('low');
                $table->timestamp('occurred_at')->nullable();
                $table->json('payload');
                $table->timestamps();
            });
        }

        $signalService  = app(AccountingSignalService::class);
        $this->service  = new VisitCostService($signalService);
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('acc_visit_costs');
        Schema::dropIfExists('acc_financial_signals');
        parent::tearDown();
    }

    /** @test */
    public function it_calculates_total_cost_correctly(): void
    {
        $payload = [
            'company_id'      => 1,
            'visit_ref'       => 'V-001',
            'site_ref'        => 'SITE-A',
            'contract_ref'    => 'CONTRACT-1',
            'labour_cost'     => 100,
            'travel_cost'     => 20,
            'equipment_cost'  => 15,
            'consumables_cost'=> 10,
            'overhead_cost'   => 5,
            'revenue'         => 200,
        ];

        $visitCost = $this->service->calculateAndStore($payload);

        $this->assertSame('150.00', (string) $visitCost->total_cost);
    }

    /** @test */
    public function it_calculates_margin_and_margin_percent(): void
    {
        $visitCost = $this->service->calculateAndStore([
            'company_id'   => 1,
            'visit_ref'    => 'V-002',
            'labour_cost'  => 60,
            'travel_cost'  => 0,
            'equipment_cost' => 0,
            'consumables_cost' => 0,
            'overhead_cost' => 0,
            'revenue'      => 100,
        ]);

        $this->assertSame('40.00', (string) $visitCost->margin);
        $this->assertSame('40.0000', (string) $visitCost->margin_percent);
    }

    /** @test */
    public function it_emits_visit_cost_calculated_event(): void
    {
        Event::fake([VisitCostCalculated::class]);

        $this->service->calculateAndStore([
            'company_id' => 1,
            'visit_ref'  => 'V-003',
            'labour_cost'=> 80,
            'revenue'    => 200,
        ]);

        Event::assertDispatched(VisitCostCalculated::class);
    }

    /** @test */
    public function it_uses_updateOrCreate_for_idempotent_recalculation(): void
    {
        $payload = ['company_id' => 1, 'visit_ref' => 'V-004', 'labour_cost' => 50, 'revenue' => 100];
        $this->service->calculateAndStore($payload);

        $payload['labour_cost'] = 70;
        $this->service->calculateAndStore($payload);

        $this->assertSame(1, VisitCost::query()->where('visit_ref', 'V-004')->count());
        $this->assertSame('70.00', (string) VisitCost::query()->where('visit_ref', 'V-004')->value('labour_cost'));
    }

    /** @test */
    public function it_emits_margin_warning_signal_for_negative_margin(): void
    {
        $this->service->calculateAndStore([
            'company_id'  => 1,
            'visit_ref'   => 'V-005',
            'labour_cost' => 200,
            'revenue'     => 100,
        ]);

        $signalCount = \Illuminate\Support\Facades\DB::table('acc_financial_signals')
            ->where('signal_name', 'account.margin.warning')
            ->where('company_id', 1)
            ->count();

        $this->assertGreaterThan(0, $signalCount);
    }

    /** @test */
    public function margin_percent_is_zero_when_revenue_is_zero(): void
    {
        $visitCost = $this->service->calculateAndStore([
            'company_id'  => 1,
            'visit_ref'   => 'V-006',
            'labour_cost' => 50,
            'revenue'     => 0,
        ]);

        $this->assertSame(0.0, (float) $visitCost->margin_percent);
    }
}
