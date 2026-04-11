<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    private array $newPermissions = [
        ['name' => 'view_own_performance',  'display_name' => 'View Own Performance'],
        ['name' => 'view_team_performance', 'display_name' => 'View Team Performance'],
        ['name' => 'manage_reviews',        'display_name' => 'Manage Performance Reviews'],
        ['name' => 'approve_reviews',       'display_name' => 'Approve Performance Reviews'],
    ];

    public function up(): void
    {
        // 1. Register permissions (Entrust / Worksuite style)
        if (Schema::hasTable('permissions')) {
            $hasModuleId = Schema::hasColumn('permissions', 'module_id');
            $moduleId = null;

            if ($hasModuleId && Schema::hasTable('modules') && Schema::hasColumn('modules', 'module_name')) {
                $module = DB::table('modules')->where('module_name', 'performance')->first();
                $moduleId = $module ? $module->id : null;
            }

            foreach ($this->newPermissions as $perm) {
                $row = [
                    'name'         => $perm['name'],
                    'display_name' => $perm['display_name'],
                    'guard_name'   => 'web',
                    'updated_at'   => now(),
                    'created_at'   => now(),
                ];

                if ($hasModuleId && $moduleId !== null) {
                    $row['module_id'] = $moduleId;
                }

                DB::table('permissions')->updateOrInsert(['name' => $perm['name']], $row);
            }
        }

        // 2. Register performance_management in subscription_package_features
        if (!Schema::hasTable('subscription_package_features') || !Schema::hasTable('subscription_packages')) {
            return;
        }

        $cols    = Schema::getColumnListing('subscription_package_features');
        $hasUuid = in_array('id', $cols, true);

        if (!in_array('feature', $cols, true)) {
            return;
        }

        $packages = DB::table('subscription_packages')->get();

        foreach ($packages as $package) {
            $exists = DB::table('subscription_package_features')
                ->where('subscription_package_id', $package->id)
                ->where('feature', 'performance_management')
                ->exists();

            if (!$exists) {
                $row = [
                    'feature'                 => 'performance_management',
                    'subscription_package_id' => $package->id,
                    'created_at'              => now(),
                    'updated_at'              => now(),
                ];

                if ($hasUuid) {
                    $row['id'] = Str::uuid()->toString();
                }

                DB::table('subscription_package_features')->insert($row);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('permissions')) {
            DB::table('permissions')
                ->whereIn('name', array_column($this->newPermissions, 'name'))
                ->delete();
        }

        if (Schema::hasTable('subscription_package_features')) {
            DB::table('subscription_package_features')
                ->where('feature', 'performance_management')
                ->delete();
        }
    }
};
