<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\BookingModule\Entities\BookingModuleSetting;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\Models\Module::validateVersion(BookingModuleSetting::MODULE_NAME);

        if (! Schema::hasTable('bookings')) {
            return;
        }
        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'total_campaign_discount_amount')) {
                $table->decimal('total_campaign_discount_amount',24,3)->default(0)->nullable();
            }
            if (! Schema::hasColumn('bookings', 'total_coupon_discount_amount')) {
                $table->decimal('total_coupon_discount_amount',24,3)->default(0)->nullable();
            }
            if (! Schema::hasColumn('bookings', 'coupon_code')) {
                $table->string('coupon_code')->nullable();
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
        Schema::table('', function (Blueprint $table) {

        });
    }
};
