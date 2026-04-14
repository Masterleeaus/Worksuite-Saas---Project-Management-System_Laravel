<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('user_zoom_meeting') && !Schema::hasColumn('user_zoom_meeting', 'company_id')) {
            Schema::table('user_zoom_meeting', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('zoom_categories') && !Schema::hasColumn('zoom_categories', 'company_id')) {
            Schema::table('zoom_categories', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('zoom_global_settings') && !Schema::hasColumn('zoom_global_settings', 'company_id')) {
            Schema::table('zoom_global_settings', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('zoom_meeting_notes') && !Schema::hasColumn('zoom_meeting_notes', 'company_id')) {
            Schema::table('zoom_meeting_notes', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('zoom_meetings') && !Schema::hasColumn('zoom_meetings', 'company_id')) {
            Schema::table('zoom_meetings', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('zoom_notification_settings') && !Schema::hasColumn('zoom_notification_settings', 'company_id')) {
            Schema::table('zoom_notification_settings', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('zoom_setting') && !Schema::hasColumn('zoom_setting', 'company_id')) {
            Schema::table('zoom_setting', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
    }

    public function down(): void
    {
        // intentionally non-destructive
    }
};
