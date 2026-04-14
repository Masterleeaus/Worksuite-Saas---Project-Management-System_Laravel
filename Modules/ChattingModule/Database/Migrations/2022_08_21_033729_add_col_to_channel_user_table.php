<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColToChannelUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('channel_users')) {
        Schema::table('channel_users', function (Blueprint $table) {
            if (!Schema::hasColumn('channel_users', 'is_read')) {
                $table->boolean('is_read')->default(0);
            }
        });
    }

        if (Schema::hasTable('channel_lists')) {
        Schema::table('channel_lists', function (Blueprint $table) {
            if (Schema::hasColumn('channel_lists', 'is_read')) {
                $table->dropColumn('is_read');
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
        Schema::table('channel_users', function (Blueprint $table) {

        });
    }
}
