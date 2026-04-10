<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('appointment_assignments') && !Schema::hasColumn('appointment_assignments', 'company_id')) {
            Schema::table('appointment_assignments', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('appointment_callbacks') && !Schema::hasColumn('appointment_callbacks', 'company_id')) {
            Schema::table('appointment_callbacks', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('appointment_notification_logs') && !Schema::hasColumn('appointment_notification_logs', 'company_id')) {
            Schema::table('appointment_notification_logs', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('appointment_notification_preferences') && !Schema::hasColumn('appointment_notification_preferences', 'company_id')) {
            Schema::table('appointment_notification_preferences', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('appointment_settings') && !Schema::hasColumn('appointment_settings', 'company_id')) {
            Schema::table('appointment_settings', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('appointment_staff_capacities') && !Schema::hasColumn('appointment_staff_capacities', 'company_id')) {
            Schema::table('appointment_staff_capacities', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('appointments') && !Schema::hasColumn('appointments', 'company_id')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('questions') && !Schema::hasColumn('questions', 'company_id')) {
            Schema::table('questions', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('schedule_assignments') && !Schema::hasColumn('schedule_assignments', 'company_id')) {
            Schema::table('schedule_assignments', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('schedules') && !Schema::hasColumn('schedules', 'company_id')) {
            Schema::table('schedules', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
    }

    public function down(): void
    {
        // intentionally non-destructive
    }
};
