<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKeyTypeColumnInNotificationSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('notification_setups')) {
        Schema::table('notification_setups', function (Blueprint $table) {
            if (!Schema::hasColumn('notification_setups', 'key_type')) {
                $table->string('key_type')->nullable();
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
        if (Schema::hasTable('notification_setups')) {
        Schema::table('notification_setups', function (Blueprint $table) {
            if (Schema::hasColumn('notification_setups', 'key_type')) {
                $table->dropColumn('key_type');
            }
        });
    }
    }
}
