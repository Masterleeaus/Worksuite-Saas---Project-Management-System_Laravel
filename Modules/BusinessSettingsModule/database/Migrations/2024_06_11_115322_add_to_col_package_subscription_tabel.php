<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddToColPackageSubscriptionTabel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('package_subscribers', function (Blueprint $table) {
            $table->foreignUuid('payment_id')->nullable();
        });
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
            if (Schema::hasColumn('package_subscribers', 'payment_id')) {
                $table->dropColumn('payment_id');
            }
        });
    }
    }
}
