<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddToColTransactionIdToPackageSubscriberLogsTabel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('package_subscriber_logs', function (Blueprint $table) {
            $table->foreignUuid('primary_transaction_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('package_subscriber_logs')) {
        Schema::table('package_subscriber_logs', function (Blueprint $table) {
            if (Schema::hasColumn('package_subscriber_logs', 'primary_transaction_id')) {
                $table->dropColumn('primary_transaction_id');
            }
        });
    }
    }
}
