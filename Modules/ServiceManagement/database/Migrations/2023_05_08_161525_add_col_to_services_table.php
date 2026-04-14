<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColToServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('services')) {
        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'min_bidding_price')) {
                $table->decimal('min_bidding_price', 24, 3)->default(0)->after('avg_rating');
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
        if (Schema::hasTable('services')) {
        Schema::table('services', function (Blueprint $table) {
            if (Schema::hasColumn('services', 'min_bidding_price')) {
                $table->dropColumn('min_bidding_price');
            }
        });
    }
    }
}
