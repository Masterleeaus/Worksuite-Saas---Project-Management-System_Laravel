<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('affiliate_global_settings') && !Schema::hasColumn('affiliate_global_settings', 'company_id')) {
            Schema::table('affiliate_global_settings', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('affiliate_payouts') && !Schema::hasColumn('affiliate_payouts', 'company_id')) {
            Schema::table('affiliate_payouts', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('affiliate_referrals') && !Schema::hasColumn('affiliate_referrals', 'company_id')) {
            Schema::table('affiliate_referrals', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('affiliate_settings') && !Schema::hasColumn('affiliate_settings', 'company_id')) {
            Schema::table('affiliate_settings', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('affiliates') && !Schema::hasColumn('affiliates', 'company_id')) {
            Schema::table('affiliates', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
    }

    public function down(): void
    {
        // intentionally non-destructive
    }
};
