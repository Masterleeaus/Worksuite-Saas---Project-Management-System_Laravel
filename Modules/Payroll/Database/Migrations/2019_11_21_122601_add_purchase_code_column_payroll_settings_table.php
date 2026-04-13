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
        if (! Schema::hasTable('payroll_settings')) {
            return;
        }
        Schema::table('payroll_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('payroll_settings', 'purchase_code')) {
                $table->string('purchase_code')->nullable();
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
        Schema::table('payroll_settings', function (Blueprint $table) {
            $table->dropColumn(['purchase_code']);
        });
    }
};
