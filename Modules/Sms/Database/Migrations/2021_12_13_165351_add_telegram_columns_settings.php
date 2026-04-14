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
        if (! Schema::hasTable('sms_settings')) {
            return;
        }
        if (!Schema::hasColumn('sms_settings', 'telegram_status')) {
            Schema::table('sms_settings', function (Blueprint $table) {
                $table->boolean('telegram_status')->default(0);
                $table->string('telegram_bot_token')->nullable();
            });
        }

        if (!Schema::hasColumn('users', 'telegram_user_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->bigInteger('telegram_user_id')->nullable();
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
        if (! Schema::hasTable('sms_settings')) {
            return;
        }
        
        Schema::table('sms_settings', function (Blueprint $table) {
            $table->dropColumn(['telegram_status']);
            $table->dropColumn(['telegram_bot_token']);
        });

        if (! Schema::hasTable('users')) {
            return;
        }
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['telegram_user_id']);
        });
    }
};
