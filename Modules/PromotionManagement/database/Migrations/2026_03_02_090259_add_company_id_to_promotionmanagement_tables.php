<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('advertisement_attachments') && !Schema::hasColumn('advertisement_attachments', 'company_id')) {
            Schema::table('advertisement_attachments', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('advertisement_notes') && !Schema::hasColumn('advertisement_notes', 'company_id')) {
            Schema::table('advertisement_notes', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('advertisement_settings') && !Schema::hasColumn('advertisement_settings', 'company_id')) {
            Schema::table('advertisement_settings', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('advertisements') && !Schema::hasColumn('advertisements', 'company_id')) {
            Schema::table('advertisements', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('banners') && !Schema::hasColumn('banners', 'company_id')) {
            Schema::table('banners', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('campaigns') && !Schema::hasColumn('campaigns', 'company_id')) {
            Schema::table('campaigns', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('coupon_customers') && !Schema::hasColumn('coupon_customers', 'company_id')) {
            Schema::table('coupon_customers', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('coupons') && !Schema::hasColumn('coupons', 'company_id')) {
            Schema::table('coupons', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('discount_types') && !Schema::hasColumn('discount_types', 'company_id')) {
            Schema::table('discount_types', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('discounts') && !Schema::hasColumn('discounts', 'company_id')) {
            Schema::table('discounts', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('push_notification_users') && !Schema::hasColumn('push_notification_users', 'company_id')) {
            Schema::table('push_notification_users', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('push_notifications') && !Schema::hasColumn('push_notifications', 'company_id')) {
            Schema::table('push_notifications', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
    }

    public function down(): void
    {
        // intentionally non-destructive
    }
};
