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
        if (! Schema::hasColumn('salary_components', 'weekly_value')) {
            if (! Schema::hasTable('salary_components')) {
                return;
            }
            
            Schema::table('salary_components', function (Blueprint $table) {
                if (! Schema::hasColumn('salary_components', 'weekly_value')) {
                    $table->double('weekly_value')->default(0)->nullable();
                }
                if (! Schema::hasColumn('salary_components', 'biweekly_value')) {
                    $table->double('biweekly_value')->default(0)->nullable();
                }
                if (! Schema::hasColumn('salary_components', 'semimonthly_value')) {
                    $table->double('semimonthly_value')->default(0)->nullable();
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

    }
};
