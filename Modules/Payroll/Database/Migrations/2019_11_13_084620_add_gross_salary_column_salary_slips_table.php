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
        if (! Schema::hasTable('salary_slips')) {
            return;
        }
        Schema::table('salary_slips', function (Blueprint $table) {
            if (! Schema::hasColumn('salary_slips', 'gross_salary')) {
                $table->double('gross_salary', 16, 2)->nullable();
            }
            if (! Schema::hasColumn('salary_slips', 'total_deductions')) {
                $table->double('total_deductions', 16, 2)->nullable();
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
        Schema::table('salary_slips', function (Blueprint $table) {
            $table->dropColumn(['gross_salary']);
            $table->dropColumn(['total_deductions']);
        });
    }
};
