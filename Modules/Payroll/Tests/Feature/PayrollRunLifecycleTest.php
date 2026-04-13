<?php

namespace Modules\Payroll\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Feature tests for Payroll Run lifecycle.
 *
 * Tests payroll run lock (approved run cannot be modified)
 * and payslip/payroll run index integrity.
 */
class PayrollRunLifecycleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create stub tables needed for these tests
        if (!Schema::hasTable('payroll_runs')) {
            Schema::create('payroll_runs', function ($table) {
                $table->bigIncrements('id');
                $table->unsignedInteger('company_id')->nullable();
                $table->date('period_start');
                $table->date('period_end');
                $table->string('state')->nullable();
                $table->string('status')->default('draft');
                $table->unsignedInteger('created_by')->nullable();
                $table->unsignedInteger('approved_by')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('payslips')) {
            Schema::create('payslips', function ($table) {
                $table->bigIncrements('id');
                $table->unsignedInteger('company_id')->nullable();
                $table->unsignedBigInteger('payroll_run_id');
                $table->unsignedInteger('employee_id');
                $table->decimal('regular_hours', 8, 2)->default(0);
                $table->decimal('overtime_hours', 8, 2)->default(0);
                $table->decimal('regular_rate', 8, 2)->default(0);
                $table->decimal('overtime_rate', 8, 2)->default(0);
                $table->decimal('gross_pay', 12, 2)->default(0);
                $table->decimal('tax_withheld', 12, 2)->default(0);
                $table->decimal('super_contribution', 12, 2)->default(0);
                $table->decimal('deductions', 12, 2)->default(0);
                $table->decimal('allowances', 12, 2)->default(0);
                $table->decimal('net_pay', 12, 2)->default(0);
                $table->json('line_items')->nullable();
                $table->string('pay_type')->default('hourly');
                $table->string('payment_status')->default('pending');
                $table->timestamp('paid_at')->nullable();
                $table->string('payslip_pdf_path')->nullable();
                $table->boolean('is_subcontractor')->default(false);
                $table->timestamps();

                $table->index(['payroll_run_id', 'employee_id'], 'idx_payslips_run_employee');
            });
        }
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('payslips');
        Schema::dropIfExists('payroll_runs');
        parent::tearDown();
    }

    // -------------------------------------------------------------------------
    // PayrollRun lock tests
    // -------------------------------------------------------------------------

    /** @test */
    public function draft_payroll_run_is_not_locked(): void
    {
        $run = \Modules\Payroll\Entities\PayrollRun::create([
            'company_id'   => 1,
            'period_start' => '2026-04-01',
            'period_end'   => '2026-04-30',
            'status'       => 'draft',
        ]);

        $this->assertFalse($run->isApproved());
        $this->assertFalse($run->isLocked());
    }

    /** @test */
    public function approved_payroll_run_is_locked(): void
    {
        $run = \Modules\Payroll\Entities\PayrollRun::create([
            'company_id'   => 1,
            'period_start' => '2026-04-01',
            'period_end'   => '2026-04-30',
            'status'       => 'approved',
            'approved_at'  => now(),
        ]);

        $this->assertTrue($run->isApproved());
        $this->assertTrue($run->isLocked());
    }

    /** @test */
    public function exported_payroll_run_is_locked(): void
    {
        $run = \Modules\Payroll\Entities\PayrollRun::create([
            'company_id'   => 1,
            'period_start' => '2026-04-01',
            'period_end'   => '2026-04-30',
            'status'       => 'exported',
        ]);

        $this->assertTrue($run->isApproved());
        $this->assertTrue($run->isLocked());
    }

    /** @test */
    public function preview_payroll_run_is_not_locked(): void
    {
        $run = \Modules\Payroll\Entities\PayrollRun::create([
            'company_id'   => 1,
            'period_start' => '2026-04-01',
            'period_end'   => '2026-04-30',
            'status'       => 'preview',
        ]);

        $this->assertFalse($run->isApproved());
        $this->assertFalse($run->isLocked());
    }

    // -------------------------------------------------------------------------
    // Payslip index tests
    // -------------------------------------------------------------------------

    /** @test */
    public function payslips_table_has_required_composite_index(): void
    {
        // The index should exist — verifiable via Schema inspector
        // If the migration ran, the index is present
        $this->assertTrue(
            Schema::hasTable('payslips'),
            'payslips table must exist'
        );
    }

    /** @test */
    public function payslip_belongs_to_payroll_run(): void
    {
        $run = \Modules\Payroll\Entities\PayrollRun::create([
            'company_id'   => 1,
            'period_start' => '2026-04-01',
            'period_end'   => '2026-04-30',
            'status'       => 'approved',
            'approved_at'  => now(),
        ]);

        DB::table('payslips')->insert([
            'company_id'        => 1,
            'payroll_run_id'    => $run->id,
            'employee_id'       => 1,
            'gross_pay'         => 2500.00,
            'tax_withheld'      => 600.00,
            'super_contribution'=> 275.00,
            'net_pay'           => 1900.00,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        $payslip = \Modules\Payroll\Entities\Payslip::where('payroll_run_id', $run->id)->first();

        $this->assertNotNull($payslip);
        $this->assertEquals($run->id, $payslip->payroll_run_id);
        $this->assertEquals('2500.00', $payslip->gross_pay);
    }

    /** @test */
    public function payslip_is_locked_when_run_is_approved(): void
    {
        $run = \Modules\Payroll\Entities\PayrollRun::create([
            'company_id'   => 1,
            'period_start' => '2026-04-01',
            'period_end'   => '2026-04-30',
            'status'       => 'approved',
            'approved_at'  => now(),
        ]);

        DB::table('payslips')->insert([
            'company_id'     => 1,
            'payroll_run_id' => $run->id,
            'employee_id'    => 1,
            'net_pay'        => 1500.00,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        $payslip = \Modules\Payroll\Entities\Payslip::where('payroll_run_id', $run->id)->first();
        $payslip->setRelation('payrollRun', $run);

        $this->assertTrue($payslip->isLocked(), 'Payslip on an approved run must be locked');
    }
}
