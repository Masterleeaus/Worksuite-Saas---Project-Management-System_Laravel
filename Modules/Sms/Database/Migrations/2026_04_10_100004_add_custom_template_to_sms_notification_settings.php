<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('sms_notification_settings') || Schema::hasColumn('sms_notification_settings', 'custom_template')) {
            return;
        }

        $afterColumn = Schema::hasColumn('sms_notification_settings', 'whatsapp_template') ? 'whatsapp_template' : null;

        Schema::table('sms_notification_settings', function (Blueprint $table) use ($afterColumn) {
            $column = $table->text('custom_template')->nullable();

            if ($afterColumn) {
                $column->after($afterColumn);
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('sms_notification_settings')) {
            return;
        }

        if (Schema::hasColumn('sms_notification_settings', 'custom_template')) {
            Schema::table('sms_notification_settings', function (Blueprint $table) {
                $table->dropColumn('custom_template');
            });
        }
    }
};
