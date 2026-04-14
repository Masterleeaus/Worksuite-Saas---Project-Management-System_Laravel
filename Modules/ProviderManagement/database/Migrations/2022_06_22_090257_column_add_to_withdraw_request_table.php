<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ColumnAddToWithdrawRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('withdraw_requests')) {
        Schema::table('withdraw_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('withdraw_requests', 'is_paid')) {
                $table->boolean('is_paid')->default(0);
            }
            if (!Schema::hasColumn('withdraw_requests', 'note')) {
                $table->string('note')->nullable();
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
        if (Schema::hasTable('withdraw_requests')) {
        Schema::table('withdraw_requests', function (Blueprint $table) {
            if (Schema::hasColumn('withdraw_requests', 'is_paid')) {
                $table->dropColumn('is_paid');
            }
            if (Schema::hasColumn('withdraw_requests', 'note')) {
                $table->dropColumn('note');
            }
        });
    }
    }
}
