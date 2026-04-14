<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColToWithdrawRequestsTable extends Migration
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
            $table->foreignUuid('withdrawal_method_id')->nullable();
            if (!Schema::hasColumn('withdraw_requests', 'withdrawal_method_fields')) {
                $table->text('withdrawal_method_fields')->nullable();
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
            if (Schema::hasColumn('withdraw_requests', 'withdrawal_method_id')) {
                $table->dropColumn('withdrawal_method_id');
            }
            if (Schema::hasColumn('withdraw_requests', 'withdrawal_method_fields')) {
                $table->dropColumn('withdrawal_method_fields');
            }
        });
    }
    }
}
