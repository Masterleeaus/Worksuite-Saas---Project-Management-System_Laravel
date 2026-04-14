<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddToColTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignUuid('booking_repeat_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('transactions')) {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'booking_repeat_id')) {
                $table->dropColumn('booking_repeat_id');
            }
        });
    }
    }
}
