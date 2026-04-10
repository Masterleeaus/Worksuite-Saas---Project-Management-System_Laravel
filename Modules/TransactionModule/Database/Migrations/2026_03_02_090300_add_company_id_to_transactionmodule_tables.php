<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('accounts') && !Schema::hasColumn('accounts', 'company_id')) {
            Schema::table('accounts', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('loyalty_point_transactions') && !Schema::hasColumn('loyalty_point_transactions', 'company_id')) {
            Schema::table('loyalty_point_transactions', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('transactions') && !Schema::hasColumn('transactions', 'company_id')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('withdrawal_methods') && !Schema::hasColumn('withdrawal_methods', 'company_id')) {
            Schema::table('withdrawal_methods', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
    }

    public function down(): void
    {
        // intentionally non-destructive
    }
};
