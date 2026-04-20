<?php

use App\Models\Company;
use App\Models\Module;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\Role;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    private array $permissions = [
        ['name' => 'view_fsm_stock_requisitions',   'display_name' => 'View FSM Stock Requisitions'],
        ['name' => 'manage_fsm_stock_requisitions',  'display_name' => 'Manage FSM Stock Requisitions'],
    ];

    public function up(): void
    {
        $module = Module::firstOrCreate(['module_name' => 'fsmsalestock'], [
            'module_name' => 'fsmsalestock',
        ]);

        foreach ($this->permissions as $def) {
            $permission = Permission::firstOrCreate(
                ['name' => $def['name'], 'module_id' => $module->id],
                [
                    'display_name'        => $def['display_name'],
                    'allowed_permissions' => Permission::ALL_NONE,
                    'is_custom'           => 1,
                ]
            );

            Company::withoutGlobalScopes()->get()->each(function (Company $company) use ($permission) {
                $role = Role::where('name', 'admin')->where('company_id', $company->id)->first();
                if ($role) {
                    PermissionRole::firstOrCreate(
                        ['permission_id' => $permission->id, 'role_id' => $role->id],
                        ['permission_type_id' => 4]
                    );
                }
            });

            User::allAdmins()->each(function (User $admin) use ($permission) {
                UserPermission::firstOrCreate(
                    ['user_id' => $admin->id, 'permission_id' => $permission->id],
                    ['permission_type_id' => 4]
                );
            });
        }
    }

    public function down(): void
    {
        $module = Module::where('module_name', 'fsmsalestock')->first();
        if (!$module) return;

        foreach ($this->permissions as $def) {
            $permission = Permission::where('name', $def['name'])->where('module_id', $module->id)->first();
            if ($permission) {
                PermissionRole::where('permission_id', $permission->id)->delete();
                UserPermission::where('permission_id', $permission->id)->delete();
                $permission->delete();
            }
        }
    }
};
