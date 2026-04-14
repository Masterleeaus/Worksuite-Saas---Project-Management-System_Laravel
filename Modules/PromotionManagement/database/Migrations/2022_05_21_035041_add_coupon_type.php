<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCouponType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons', function($table) {
            $table->string('coupon_type',191)->after('id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('coupons')) {
        Schema::table('coupons', function (Blueprint $table) {
            if (Schema::hasColumn('coupons', 'coupon_type')) {
                $table->dropColumn('coupon_type');
            }
        });
    }
    }
}
