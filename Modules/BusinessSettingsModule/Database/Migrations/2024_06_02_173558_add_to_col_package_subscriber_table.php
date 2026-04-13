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
        if (! Schema::hasTable('package_subscribers')) {
            return;
        }
        Schema::table('package_subscribers', function (Blueprint $table) {
            if (! Schema::hasColumn('package_subscribers', 'is_canceled')) {
                $table->tinyInteger('is_canceled')->default(0)->nullable();
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
        Schema::table('package_subscribers', function (Blueprint $table) {
            $table->dropColumn('is_canceled');
        });
    }
}
