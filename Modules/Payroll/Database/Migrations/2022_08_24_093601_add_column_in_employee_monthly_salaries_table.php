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
        if (! Schema::hasTable('employee_monthly_salaries')) {
            return;
        }
        Schema::table('employee_monthly_salaries', function (Blueprint $table) {
            if (! Schema::hasColumn('employee_monthly_salaries', 'annual_salary')) {
                $table->string('annual_salary')->after('user_id')->nullable();
            }
            if (! Schema::hasColumn('employee_monthly_salaries', 'basic_salary')) {
                $table->string('basic_salary')->after('amount')->nullable();
            }
            if (! Schema::hasColumn('employee_monthly_salaries', 'basic_value_type')) {
                $table->enum('basic_value_type', ['fixed', 'ctc_percent'])->default(null)->after('basic_salary')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('', function (Blueprint $table) {

        });
    }
};
