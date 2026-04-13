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
            if (! Schema::hasColumn('bookings', 'additional_tax_amount')) {
                $table->decimal('additional_tax_amount',24,2)->default(0)->nullable();
            }
            if (! Schema::hasColumn('bookings', 'additional_discount_amount')) {
                $table->decimal('additional_discount_amount',24,2)->default(0)->nullable();
            }
            if (! Schema::hasColumn('bookings', 'additional_campaign_discount_amount')) {
                $table->decimal('additional_campaign_discount_amount',24,2)->default(0)->nullable();
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
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('additional_tax_amount');
            $table->dropColumn('additional_discount_amount');
            $table->dropColumn('additional_campaign_discount_amount');
        });
    }
};
