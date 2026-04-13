<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('email_notification_settings')) {
            return;
        }
        Schema::table('email_notification_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('email_notification_settings', 'send_twilio')) {
                $table->enum('send_twilio', ['yes', 'no'])->default('no')->nullable();
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
        Schema::table('email_notification_settings', function (Blueprint $table) {
            $table->dropColumn(['send_twilio']);
        });
    }
};
