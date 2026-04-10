<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('booking_additional_information') && !Schema::hasColumn('booking_additional_information', 'company_id')) {
            Schema::table('booking_additional_information', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('booking_details') && !Schema::hasColumn('booking_details', 'company_id')) {
            Schema::table('booking_details', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('booking_details_amounts') && !Schema::hasColumn('booking_details_amounts', 'company_id')) {
            Schema::table('booking_details_amounts', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('booking_ignores') && !Schema::hasColumn('booking_ignores', 'company_id')) {
            Schema::table('booking_ignores', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('booking_offline_payments') && !Schema::hasColumn('booking_offline_payments', 'company_id')) {
            Schema::table('booking_offline_payments', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('booking_partial_payments') && !Schema::hasColumn('booking_partial_payments', 'company_id')) {
            Schema::table('booking_partial_payments', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('booking_repeat_details') && !Schema::hasColumn('booking_repeat_details', 'company_id')) {
            Schema::table('booking_repeat_details', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('booking_repeat_histories') && !Schema::hasColumn('booking_repeat_histories', 'company_id')) {
            Schema::table('booking_repeat_histories', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('booking_repeats') && !Schema::hasColumn('booking_repeats', 'company_id')) {
            Schema::table('booking_repeats', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('booking_schedule_histories') && !Schema::hasColumn('booking_schedule_histories', 'company_id')) {
            Schema::table('booking_schedule_histories', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('booking_status_histories') && !Schema::hasColumn('booking_status_histories', 'company_id')) {
            Schema::table('booking_status_histories', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('bookings') && !Schema::hasColumn('bookings', 'company_id')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('subscription_booking_types') && !Schema::hasColumn('subscription_booking_types', 'company_id')) {
            Schema::table('subscription_booking_types', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('subscription_subscriber_bookings') && !Schema::hasColumn('subscription_subscriber_bookings', 'company_id')) {
            Schema::table('subscription_subscriber_bookings', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
    }

    public function down(): void
    {
        // intentionally non-destructive
    }
};
