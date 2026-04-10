<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('sms_notification_settings') && !Schema::hasColumn('sms_notification_settings', 'company_id')) {
            Schema::table('sms_notification_settings', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('sms_settings') && !Schema::hasColumn('sms_settings', 'company_id')) {
            Schema::table('sms_settings', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('sms_template_ids') && !Schema::hasColumn('sms_template_ids', 'company_id')) {
            Schema::table('sms_template_ids', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
    }

    public function down(): void
    {
        // intentionally non-destructive
    }
};
