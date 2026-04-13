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
        if (! Schema::hasTable('channel_users')) {
            return;
        }
        Schema::table('channel_users', function (Blueprint $table) {
            if (! Schema::hasColumn('channel_users', 'is_read')) {
                $table->boolean('is_read')->default(0)->nullable();
            }
        });

        if (! Schema::hasTable('channel_lists')) {
            return;
        }
        
        Schema::table('channel_lists', function (Blueprint $table) {
            $table->dropColumn('is_read');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (! Schema::hasTable('channel_users')) {
            return;
        }
        
        Schema::table('channel_users', function (Blueprint $table) {

        });
    }
}
