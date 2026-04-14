<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsNotifiedInPackageSubscribers extends Migration
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
            if (!Schema::hasColumn('package_subscribers', 'is_notified')) {
                $table->tinyInteger('is_notified')->default(0);
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
            if (Schema::hasColumn('package_subscribers', 'is_notified')) {
                $table->dropColumn('is_notified');
            }
        });
    }
    }
}
