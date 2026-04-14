<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $moduleName = 'titantheme';

        if (!Schema::hasTable('modules')) {
            return;
        }

        $moduleId = DB::table('modules')->where('module_name', $moduleName)->value('id');

        if (!$moduleId) {
            $moduleId = DB::table('modules')->insertGetId([
                'module_name' => $moduleName,
                'description' => 'TitanTheme module',
                'is_superadmin' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (Schema::hasTable('permissions')) {
            $permissions = [
                'view_theme_settings' => 'View Theme Settings',
                'manage_theme_settings' => 'Manage Theme Settings',
                'view_mega_menu' => 'View Mega Menu',
                'manage_mega_menu' => 'Manage Mega Menu',
                'view_navigation' => 'View Navigation',
                'manage_navigation' => 'Manage Navigation',
                'manage_white_label' => 'Manage White Label',
            ];

            foreach ($permissions as $permissionName => $displayName) {
                $permissionId = DB::table('permissions')->where('name', $permissionName)->value('id');

                if (!$permissionId) {
                    DB::table('permissions')->insert([
                        'name' => $permissionName,
                        'display_name' => $displayName,
                        'module_id' => $moduleId,
                        'is_custom' => 1,
                        'allowed_permissions' => '["all","none"]',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                else {
                    DB::table('permissions')->where('id', $permissionId)->update([
                        'display_name' => $displayName,
                        'module_id' => $moduleId,
                        'is_custom' => 1,
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        $allPermissionTypeId = Schema::hasTable('permission_types')
            ? (DB::table('permission_types')->where('name', 'all')->value('id') ?: 4)
            : 4;

        $permissionIds = Schema::hasTable('permissions')
            ? DB::table('permissions')->whereIn('name', [
                'view_theme_settings',
                'manage_theme_settings',
                'view_mega_menu',
                'manage_mega_menu',
                'view_navigation',
                'manage_navigation',
                'manage_white_label',
            ])->pluck('id')
            : collect();

        if (Schema::hasTable('companies')) {
            $companies = DB::table('companies')->select('id', 'package_id')->get();

            foreach ($companies as $company) {
                if (Schema::hasTable('roles') && Schema::hasTable('permission_role')) {
                    $adminRoleIds = DB::table('roles')
                        ->where('company_id', $company->id)
                        ->where('name', 'admin')
                        ->pluck('id');

                    foreach ($adminRoleIds as $roleId) {
                        foreach ($permissionIds as $permissionId) {
                            $exists = DB::table('permission_role')
                                ->where('permission_id', $permissionId)
                                ->where('role_id', $roleId)
                                ->exists();

                            if (!$exists) {
                                DB::table('permission_role')->insert([
                                    'permission_id' => $permissionId,
                                    'role_id' => $roleId,
                                    'permission_type_id' => $allPermissionTypeId,
                                ]);
                            }
                            else {
                                DB::table('permission_role')
                                    ->where('permission_id', $permissionId)
                                    ->where('role_id', $roleId)
                                    ->update(['permission_type_id' => $allPermissionTypeId]);
                            }
                        }
                    }
                }

                if (Schema::hasTable('role_user') && Schema::hasTable('user_permissions') && Schema::hasTable('roles')) {
                    $adminUserIds = DB::table('role_user')
                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                        ->where('roles.company_id', $company->id)
                        ->where('roles.name', 'admin')
                        ->pluck('role_user.user_id')
                        ->unique();

                    foreach ($adminUserIds as $userId) {
                        foreach ($permissionIds as $permissionId) {
                            $exists = DB::table('user_permissions')
                                ->where('user_id', $userId)
                                ->where('permission_id', $permissionId)
                                ->exists();

                            if (!$exists) {
                                DB::table('user_permissions')->insert([
                                    'user_id' => $userId,
                                    'permission_id' => $permissionId,
                                    'permission_type_id' => $allPermissionTypeId,
                                ]);
                            }
                            else {
                                DB::table('user_permissions')
                                    ->where('user_id', $userId)
                                    ->where('permission_id', $permissionId)
                                    ->update(['permission_type_id' => $allPermissionTypeId]);
                            }
                        }
                    }
                }

                if (Schema::hasTable('module_settings')) {
                    $package = Schema::hasTable('packages') && $company->package_id
                        ? DB::table('packages')->select('module_in_package')->where('id', $company->package_id)->first()
                        : null;

                    $packageModules = collect(json_decode($package?->module_in_package ?: '[]', true) ?: []);
                    $isAllowed = $packageModules->contains($moduleName);

                    foreach (['admin', 'employee', 'client'] as $type) {
                        $exists = DB::table('module_settings')
                            ->where('company_id', $company->id)
                            ->where('type', $type)
                            ->where('module_name', $moduleName)
                            ->exists();

                        $payload = [
                            'status' => $isAllowed ? 'active' : 'deactive',
                            'updated_at' => now(),
                        ];

                        if (Schema::hasColumn('module_settings', 'is_allowed')) {
                            $payload['is_allowed'] = $isAllowed ? 1 : 0;
                        }

                        if (!$exists) {
                            DB::table('module_settings')->insert([
                                'company_id' => $company->id,
                                'module_name' => $moduleName,
                                'type' => $type,
                                'status' => $isAllowed ? 'active' : 'deactive',
                                'created_at' => now(),
                                'updated_at' => now(),
                                ...(Schema::hasColumn('module_settings', 'is_allowed') ? ['is_allowed' => $isAllowed ? 1 : 0] : []),
                            ]);
                        }
                        else {
                            DB::table('module_settings')
                                ->where('company_id', $company->id)
                                ->where('type', $type)
                                ->where('module_name', $moduleName)
                                ->update($payload);
                        }
                    }
                }
            }
        }

        if (Schema::hasTable('packages') && Schema::hasColumn('packages', 'module_in_package')) {
            DB::table('packages')->select('id', 'module_in_package')->orderBy('id')->chunkById(50, function ($packages) use ($moduleName) {
                foreach ($packages as $package) {
                    $decoded = json_decode($package->module_in_package ?: '[]', true);

                    if (!is_array($decoded)) {
                        $decoded = collect(explode(',', (string) $package->module_in_package))
                            ->map(fn ($item) => trim($item, " \t\n\r\0\x0B\""))
                            ->filter()
                            ->values()
                            ->all();
                    }

                    if (!in_array($moduleName, $decoded, true)) {
                        $decoded[] = $moduleName;
                        DB::table('packages')
                            ->where('id', $package->id)
                            ->update(['module_in_package' => json_encode(array_values(array_unique($decoded)))]);
                    }
                }
            }, 'id');
        }
    }

    public function down(): void
    {
        // Safe no-op rollback.
    }
};
