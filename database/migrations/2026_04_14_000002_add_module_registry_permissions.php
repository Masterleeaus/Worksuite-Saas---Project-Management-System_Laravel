<?php

use App\Models\Module;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\Role;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    private array $permissionNames = [
        'manage_modules_registry',
        'view_modules_registry',
        'run_module_repairs',
        'install_custom_modules',
    ];

    public function up(): void
    {
        // Find the superadmin settings module (the one that has is_superadmin=1 and
        // already contains manage_superadmin_custom_module_settings).
        $settingsModule = Module::withoutGlobalScopes()
            ->where('is_superadmin', 1)
            ->whereHas('customPermissions', function ($q) {
                $q->where('name', 'manage_superadmin_custom_module_settings');
            })
            ->first();

        if (! $settingsModule) {
            return;
        }

        $superadminRole = Role::withoutGlobalScope(\App\Scopes\CompanyScope::class)
            ->whereNull('company_id')
            ->where('name', 'superadmin')
            ->first();

        foreach ($this->permissionNames as $name) {
            $permission = Permission::updateOrCreate(
                [
                    'name'      => $name,
                    'module_id' => $settingsModule->id,
                ],
                [
                    'display_name'         => ucwords(str_replace('_', ' ', $name)),
                    'is_custom'            => 1,
                    'allowed_permissions'  => Permission::ALL_NONE,
                ]
            );

            // Attach to superadmin role
            if ($superadminRole) {
                PermissionRole::firstOrCreate(
                    [
                        'permission_id' => $permission->id,
                        'role_id'       => $superadminRole->id,
                    ],
                    ['permission_type_id' => 4]
                );
            }

            // Attach to all superadmin users
            $superadmins = User::withoutGlobalScopes()
                ->whereNull('company_id')
                ->where('is_superadmin', 1)
                ->get();

            foreach ($superadmins as $user) {
                UserPermission::firstOrCreate(
                    [
                        'user_id'       => $user->id,
                        'permission_id' => $permission->id,
                    ],
                    ['permission_type_id' => 4]
                );
            }
        }
    }

    public function down(): void
    {
        $settingsModule = Module::withoutGlobalScopes()
            ->where('is_superadmin', 1)
            ->whereHas('customPermissions', function ($q) {
                $q->where('name', 'manage_superadmin_custom_module_settings');
            })
            ->first();

        if (! $settingsModule) {
            return;
        }

        foreach ($this->permissionNames as $name) {
            $permission = Permission::where('name', $name)
                ->where('module_id', $settingsModule->id)
                ->first();

            if ($permission) {
                PermissionRole::where('permission_id', $permission->id)->delete();
                UserPermission::where('permission_id', $permission->id)->delete();
                $permission->delete();
            }
        }
    }
};
