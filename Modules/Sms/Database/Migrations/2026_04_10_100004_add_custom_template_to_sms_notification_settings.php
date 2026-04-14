<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('sms_notification_settings', 'custom_template')) {
            Schema::table('sms_notification_settings', function (Blueprint $table) {
                $table->text('custom_template')->nullable()->after('whatsapp_template');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('sms_notification_settings')) {
            return;
        }
        
        Schema::table('sms_notification_settings', function (Blueprint $table) {
            $table->dropColumn('custom_template');
        });
    }
};
