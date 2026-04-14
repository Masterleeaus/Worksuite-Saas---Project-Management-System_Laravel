<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('employee_details')) {
            return;
        }
        
        Schema::table('employee_details', function (Blueprint $table) {
            if (!Schema::hasColumn('employee_details', 'pay_type')) {
                $table->string('pay_type')->default('hourly')
                    ->comment('hourly, daily, per_job, salary');
            }

            if (!Schema::hasColumn('employee_details', 'hourly_rate')) {
                $table->decimal('hourly_rate', 8, 2)->nullable();
            }

            if (!Schema::hasColumn('employee_details', 'daily_rate')) {
                $table->decimal('daily_rate', 8, 2)->nullable();
            }

            if (!Schema::hasColumn('employee_details', 'per_job_rate')) {
                $table->decimal('per_job_rate', 8, 2)->nullable()
                    ->comment('Rate per completed booking/job (FSM per_job pay type)');
            }

            if (!Schema::hasColumn('employee_details', 'annual_salary')) {
                $table->decimal('annual_salary', 12, 2)->nullable();
            }

            if (!Schema::hasColumn('employee_details', 'super_rate')) {
                // AU 2024 default: 11.00%
                $table->decimal('super_rate', 5, 4)->default(0.1100)
                    ->comment('Superannuation rate (decimal, e.g. 0.1100 = 11%)');
            }

            if (!Schema::hasColumn('employee_details', 'tax_basis')) {
                $table->string('tax_basis')->default('weekly')
                    ->comment('ATO tax table basis: weekly, fortnightly, monthly');
            }

            if (!Schema::hasColumn('employee_details', 'is_subcontractor')) {
                $table->boolean('is_subcontractor')->default(false)
                    ->comment('Subcontractors receive remittance advice; no PAYG withholding');
            }

            if (!Schema::hasColumn('employee_details', 'casual_loading')) {
                $table->decimal('casual_loading', 5, 4)->default(0.0000)
                    ->comment('Casual loading rate (e.g. 0.25 = 25% loading on base rate)');
            }

            if (!Schema::hasColumn('employee_details', 'bank_bsb')) {
                $table->string('bank_bsb', 7)->nullable()
                    ->comment('BSB in NNN-NNN format for ABA file generation');
            }

            if (!Schema::hasColumn('employee_details', 'bank_account_number')) {
                $table->string('bank_account_number', 20)->nullable()
                    ->comment('Bank account number for ABA file generation');
            }

            if (!Schema::hasColumn('employee_details', 'bank_account_name')) {
                $table->string('bank_account_name', 32)->nullable()
                    ->comment('Account name for ABA file (max 32 chars)');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('employee_details')) {
            return;
        }
        
        Schema::table('employee_details', function (Blueprint $table) {
            $columns = [
                'pay_type', 'hourly_rate', 'daily_rate', 'per_job_rate',
                'annual_salary', 'super_rate', 'tax_basis', 'is_subcontractor',
                'casual_loading', 'bank_bsb', 'bank_account_number', 'bank_account_name',
            ];

            foreach ($columns as $col) {
                if (Schema::hasColumn('employee_details', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
