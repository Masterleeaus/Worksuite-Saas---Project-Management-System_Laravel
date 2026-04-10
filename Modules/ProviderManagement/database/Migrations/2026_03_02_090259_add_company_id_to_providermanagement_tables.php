<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('bank_details') && !Schema::hasColumn('bank_details', 'company_id')) {
            Schema::table('bank_details', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('favorite_providers') && !Schema::hasColumn('favorite_providers', 'company_id')) {
            Schema::table('favorite_providers', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('provider_settings') && !Schema::hasColumn('provider_settings', 'company_id')) {
            Schema::table('provider_settings', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('provider_sub_category') && !Schema::hasColumn('provider_sub_category', 'company_id')) {
            Schema::table('provider_sub_category', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('providers') && !Schema::hasColumn('providers', 'company_id')) {
            Schema::table('providers', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('providers_withdraw_methods_data') && !Schema::hasColumn('providers_withdraw_methods_data', 'company_id')) {
            Schema::table('providers_withdraw_methods_data', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('subscribed_services') && !Schema::hasColumn('subscribed_services', 'company_id')) {
            Schema::table('subscribed_services', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('withdraw_requests') && !Schema::hasColumn('withdraw_requests', 'company_id')) {
            Schema::table('withdraw_requests', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
    }

    public function down(): void
    {
        // intentionally non-destructive
    }
};
