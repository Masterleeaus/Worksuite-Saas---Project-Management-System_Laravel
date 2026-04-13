<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('payslips')) {
            return;
        }

        Schema::create('payslips', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('company_id')->unsigned()->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedBigInteger('payroll_run_id');
            $table->foreign('payroll_run_id')->references('id')->on('payroll_runs')->onDelete('cascade');
            $table->integer('employee_id')->unsigned();
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');

            // Hours and rates
            $table->decimal('regular_hours', 8, 2)->default(0);
            $table->decimal('overtime_hours', 8, 2)->default(0);
            $table->decimal('regular_rate', 8, 2)->default(0);
            $table->decimal('overtime_rate', 8, 2)->default(0);

            // Pay components (stored as strings to preserve bcmath precision)
            $table->decimal('gross_pay', 12, 2)->default(0);
            $table->decimal('tax_withheld', 12, 2)->default(0);
            $table->decimal('super_contribution', 12, 2)->default(0);
            $table->decimal('deductions', 12, 2)->default(0);
            $table->decimal('allowances', 12, 2)->default(0);
            $table->decimal('net_pay', 12, 2)->default(0);

            // Line item breakdown
            $table->json('line_items')->nullable();

            // Pay type used for this payslip
            $table->string('pay_type')->default('hourly')->comment('hourly, daily, per_job, salary');

            // Status and payment
            $table->string('payment_status')->default('pending')->comment('pending, paid, cancelled');
            $table->timestamp('paid_at')->nullable();
            $table->string('payslip_pdf_path')->nullable();

            // Subcontractor flag (no tax withheld, remittance advice instead)
            $table->boolean('is_subcontractor')->default(false);

            $table->timestamps();

            // Index for fast lookup per payroll run + employee
            $table->index(['payroll_run_id', 'employee_id'], 'idx_payslips_run_employee');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payslips');
    }
};
