<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('webhooks_global_settings') && !Schema::hasColumn('webhooks_global_settings', 'company_id')) {
            Schema::table('webhooks_global_settings', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('webhooks_logs') && !Schema::hasColumn('webhooks_logs', 'company_id')) {
            Schema::table('webhooks_logs', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('webhooks_requests') && !Schema::hasColumn('webhooks_requests', 'company_id')) {
            Schema::table('webhooks_requests', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('webhooks_settings') && !Schema::hasColumn('webhooks_settings', 'company_id')) {
            Schema::table('webhooks_settings', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
    }

    public function down(): void
    {
        // intentionally non-destructive
    }
};
