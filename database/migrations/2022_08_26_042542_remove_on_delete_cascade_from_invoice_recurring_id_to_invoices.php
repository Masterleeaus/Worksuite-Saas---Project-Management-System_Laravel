<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        if (\DB::getDriverName() === 'sqlite' || !Schema::hasTable('invoices') || !Schema::hasColumn('invoices', 'invoice_recurring_id')) {
            return;
        }

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign('invoices_invoice_recurring_id_foreign');
            $table->foreign('invoice_recurring_id')
                ->references('id')->on('invoice_recurring')
                ->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (\DB::getDriverName() === 'sqlite' || !Schema::hasTable('invoices')) {
            return;
        }

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign('invoices_invoice_recurring_id_foreign');
        });
    }

};
