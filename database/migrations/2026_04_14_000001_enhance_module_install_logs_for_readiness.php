<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('module_install_logs')) {
            return;
        }

        Schema::table('module_install_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('module_install_logs', 'status')) {
                $table->string('status', 50)->nullable()->after('recommended_mode');
            }
            if (!Schema::hasColumn('module_install_logs', 'manifest_data')) {
                $table->json('manifest_data')->nullable()->after('package_summary');
            }
            if (!Schema::hasColumn('module_install_logs', 'validation_results')) {
                $table->json('validation_results')->nullable()->after('manifest_data');
            }
            if (!Schema::hasColumn('module_install_logs', 'validation_score')) {
                $table->unsignedInteger('validation_score')->default(0)->after('validation_results');
            }
            if (!Schema::hasColumn('module_install_logs', 'readiness_flags')) {
                $table->json('readiness_flags')->nullable()->after('validation_score');
            }
            if (!Schema::hasColumn('module_install_logs', 'readiness_score')) {
                $table->unsignedInteger('readiness_score')->default(0)->after('readiness_flags');
            }
            if (!Schema::hasColumn('module_install_logs', 'fix_plan')) {
                $table->json('fix_plan')->nullable()->after('readiness_score');
            }
            if (!Schema::hasColumn('module_install_logs', 'repair_results')) {
                $table->json('repair_results')->nullable()->after('fix_plan');
            }
            if (!Schema::hasColumn('module_install_logs', 'applied_repairs')) {
                $table->json('applied_repairs')->nullable()->after('repair_results');
            }
            if (!Schema::hasColumn('module_install_logs', 'pre_install_snapshot')) {
                $table->json('pre_install_snapshot')->nullable()->after('applied_repairs');
            }
            if (!Schema::hasColumn('module_install_logs', 'can_rollback')) {
                $table->boolean('can_rollback')->default(false)->after('pre_install_snapshot');
            }
            if (!Schema::hasColumn('module_install_logs', 'installed_at')) {
                $table->timestamp('installed_at')->nullable()->after('can_rollback');
            }
            if (!Schema::hasColumn('module_install_logs', 'last_verified_at')) {
                $table->timestamp('last_verified_at')->nullable()->after('installed_at');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('module_install_logs')) {
            return;
        }

        Schema::table('module_install_logs', function (Blueprint $table) {
            foreach (['last_verified_at','installed_at','can_rollback','pre_install_snapshot','applied_repairs','repair_results','fix_plan','readiness_score','readiness_flags','validation_score','validation_results','manifest_data','status'] as $column) {
                if (Schema::hasColumn('module_install_logs', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
