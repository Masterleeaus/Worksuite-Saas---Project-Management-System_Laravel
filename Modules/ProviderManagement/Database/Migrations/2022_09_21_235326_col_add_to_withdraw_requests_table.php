<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ColAddToWithdrawRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('withdraw_requests')) {
            return;
        }
        if (!Schema::hasColumn('withdraw_requests', 'admin_note')) {
            Schema::table('withdraw_requests', function (Blueprint $table) {
                $table->string('admin_note')->nullable();
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
        Schema::table('withdraw_requests', function (Blueprint $table) {
            $table->dropColumn('admin_note');
        });
    }
}
