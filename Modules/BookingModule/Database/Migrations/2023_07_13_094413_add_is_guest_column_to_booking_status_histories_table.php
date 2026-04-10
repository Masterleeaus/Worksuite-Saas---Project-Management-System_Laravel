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

        if (! Schema::hasTable('booking_status_histories')) {
            return;
        }
        Schema::table('booking_status_histories', function (Blueprint $table) {
            $table->boolean('is_guest')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_status_histories', function (Blueprint $table) {
            $table->dropColumn('is_guest');
        });
    }
};
