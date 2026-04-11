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

        if (! Schema::hasTable('booking_offline_payments')) {
            return;
        }
        Schema::table('booking_offline_payments', function (Blueprint $table) {
            $table->foreignUuid('offline_payment_id')->nullable()->after('booking_id');
            $table->enum('payment_status', ['pending', 'denied', 'approved'])->default('approved')->after('customer_information');
            $table->text('denied_note')->nullable()->after('payment_status');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_offline_payments', function (Blueprint $table) {
            $table->dropColumn('offline_payment_id');
            $table->dropColumn('payment_status');
            $table->dropColumn('denied_note');
        });
    }
};
