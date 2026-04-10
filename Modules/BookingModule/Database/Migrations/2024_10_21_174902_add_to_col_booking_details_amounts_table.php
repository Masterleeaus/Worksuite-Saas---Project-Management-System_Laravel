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

        Schema::table('booking_details_amounts', function (Blueprint $table) {
            $table->foreignUuid('booking_repeat_id')->nullable();
            $table->foreignUuid('booking_repeat_details_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_details_amounts', function (Blueprint $table) {
            $table->dropColumn('booking_repeat_id');
            $table->dropColumn('booking_repeat_details_id');
        });
    }
};
