<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ColAddToTnxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('transactions')) {
            return;
        }
        Schema::table('transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('transactions', 'from_user_account')) {
                $table->string('from_user_account')->nullable();
            }
            if (! Schema::hasColumn('transactions', 'to_user_account')) {
                $table->string('to_user_account')->nullable();
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
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('from_user_account');
            $table->dropColumn('to_user_account');
        });
    }
}
