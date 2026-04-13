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
            if (! Schema::hasColumn('salary_slips', 'tds')) {
                $table->double('tds', 16, 2)->nullable();
            }
            if (! Schema::hasColumn('salary_slips', 'monthly_salary')) {
                $table->double('monthly_salary', 16, 2)->nullable();
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
            $table->dropColumn(['tds']);
            $table->dropColumn(['monthly_salary']);
        });
    }
};
