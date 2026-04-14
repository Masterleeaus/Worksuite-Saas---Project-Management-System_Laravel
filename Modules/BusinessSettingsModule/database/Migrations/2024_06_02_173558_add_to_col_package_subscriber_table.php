<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddToColPackageSubscriberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('package_subscribers')) {
        Schema::table('package_subscribers', function (Blueprint $table) {
            if (!Schema::hasColumn('package_subscribers', 'is_canceled')) {
                $table->tinyInteger('is_canceled')->default(0);
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
        if (Schema::hasTable('package_subscribers')) {
        Schema::table('package_subscribers', function (Blueprint $table) {
            if (Schema::hasColumn('package_subscribers', 'is_canceled')) {
                $table->dropColumn('is_canceled');
            }
        });
    }
    }
}
