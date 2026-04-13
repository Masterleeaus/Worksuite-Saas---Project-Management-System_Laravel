<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn('payroll_settings', 'extra_fields')) {
            Schema::table('payroll_settings', function (Blueprint $table) {
                $table->text('extra_fields')->nullable();
            });

            if (! Schema::hasTable('employee_monthly_salaries')) {
                return;
            }
            
            Schema::table('employee_monthly_salaries', function (Blueprint $table) {
                if (! Schema::hasColumn('employee_monthly_salaries', 'allow_generate_payroll')) {
                    $table->enum('allow_generate_payroll', ['yes', 'no'])->default('yes')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (! Schema::hasTable('payroll_settings')) {
            return;
        }
        
        Schema::table('payroll_settings', function (Blueprint $table) {
            $table->dropColumn('extra_fields');
        });

        if (! Schema::hasTable('employee_monthly_salaries')) {
            return;
        }
        
        Schema::table('employee_monthly_salaries', function (Blueprint $table) {
            $table->dropColumn('allow_generate_payroll');
        });
    }
};
