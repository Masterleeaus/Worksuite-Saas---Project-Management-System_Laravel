<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('workflow_logs') && !Schema::hasColumn('workflow_logs', 'company_id')) {
            Schema::table('workflow_logs', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('workflow_run_steps') && !Schema::hasColumn('workflow_run_steps', 'company_id')) {
            Schema::table('workflow_run_steps', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('workflow_runs') && !Schema::hasColumn('workflow_runs', 'company_id')) {
            Schema::table('workflow_runs', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('workflow_settings') && !Schema::hasColumn('workflow_settings', 'company_id')) {
            Schema::table('workflow_settings', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('workflow_steps') && !Schema::hasColumn('workflow_steps', 'company_id')) {
            Schema::table('workflow_steps', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('workflows') && !Schema::hasColumn('workflows', 'company_id')) {
            Schema::table('workflows', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
    }

    public function down(): void
    {
        // intentionally non-destructive
    }
};
