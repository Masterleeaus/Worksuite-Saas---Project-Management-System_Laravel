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
        if (Schema::hasTable('sms_settings')) {
        Schema::table('sms_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('sms_settings', 'telegram_status')) {
                $table->boolean('telegram_status')->default(0);
            }
            if (!Schema::hasColumn('sms_settings', 'telegram_bot_token')) {
                $table->string('telegram_bot_token')->nullable();
            }
        });
    }

        if (Schema::hasTable('users')) {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'telegram_user_id')) {
                $table->bigInteger('telegram_user_id')->nullable();
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
        Schema::table('sms_settings', function (Blueprint $table) {
            $table->dropColumn(['telegram_status']);
            $table->dropColumn(['telegram_bot_token']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['telegram_user_id']);
        });
    }
};
