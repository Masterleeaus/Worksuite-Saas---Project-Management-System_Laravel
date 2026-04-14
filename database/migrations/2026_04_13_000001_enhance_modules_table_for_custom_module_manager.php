<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('modules')) {
            return;
        }

        Schema::table('modules', function (Blueprint $table) {
            $columns = [
                'slug' => fn () => $table->string('slug')->nullable()->after('module_name'),
                'display_name' => fn () => $table->string('display_name')->nullable()->after('slug'),
                'provider_class' => fn () => $table->string('provider_class')->nullable()->after('description'),
                'module_path' => fn () => $table->string('module_path')->nullable()->after('provider_class'),
                'installed_version' => fn () => $table->string('installed_version')->nullable()->after('module_path'),
                'available_version' => fn () => $table->string('available_version')->nullable()->after('installed_version'),
                'install_source' => fn () => $table->string('install_source')->nullable()->after('available_version'),
                'health_status' => fn () => $table->string('health_status')->nullable()->after('install_source'),
                'category' => fn () => $table->string('category')->nullable()->after('health_status'),
                'icon' => fn () => $table->string('icon')->nullable()->after('category'),
                'is_installed' => fn () => $table->boolean('is_installed')->default(false)->after('is_superadmin'),
                'is_discovered' => fn () => $table->boolean('is_discovered')->default(false)->after('is_installed'),
                'is_enabled' => fn () => $table->boolean('is_enabled')->default(false)->after('is_discovered'),
                'is_visible_in_packages' => fn () => $table->boolean('is_visible_in_packages')->default(true)->after('is_enabled'),
                'is_assignable_to_plans' => fn () => $table->boolean('is_assignable_to_plans')->default(true)->after('is_visible_in_packages'),
                'has_migrations' => fn () => $table->boolean('has_migrations')->default(false)->after('is_assignable_to_plans'),
                'has_menu' => fn () => $table->boolean('has_menu')->default(false)->after('has_migrations'),
                'auto_enable_on_install' => fn () => $table->boolean('auto_enable_on_install')->default(true)->after('has_menu'),
                'last_scanned_at' => fn () => $table->timestamp('last_scanned_at')->nullable()->after('auto_enable_on_install'),
                'last_doctor_run_at' => fn () => $table->timestamp('last_doctor_run_at')->nullable()->after('last_scanned_at'),
                'last_enabled_at' => fn () => $table->timestamp('last_enabled_at')->nullable()->after('last_doctor_run_at'),
            ];

            foreach ($columns as $name => $callback) {
                if (!Schema::hasColumn('modules', $name)) {
                    $callback();
                }
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('modules')) {
            return;
        }

        Schema::table('modules', function (Blueprint $table) {
            foreach ([
                'slug','display_name','provider_class','module_path','installed_version','available_version','install_source','health_status','category','icon',
                'is_installed','is_discovered','is_enabled','is_visible_in_packages','is_assignable_to_plans','has_migrations','has_menu','auto_enable_on_install',
                'last_scanned_at','last_doctor_run_at','last_enabled_at'
            ] as $column) {
                if (Schema::hasColumn('modules', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
