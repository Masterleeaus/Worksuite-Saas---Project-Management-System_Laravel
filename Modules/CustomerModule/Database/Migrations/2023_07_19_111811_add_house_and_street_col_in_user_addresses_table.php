<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHouseAndStreetColInUserAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('user_addresses')) {
            return;
        }
        Schema::table('user_addresses', function (Blueprint $table) {
            if (! Schema::hasColumn('user_addresses', 'house')) {
                $table->string('house')->nullable();
            }
            if (! Schema::hasColumn('user_addresses', 'floor')) {
                $table->string('floor')->nullable();
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
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->dropColumn('house');
            $table->dropColumn('floor');
        });
    }
}
