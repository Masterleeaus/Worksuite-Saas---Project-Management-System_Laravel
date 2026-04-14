<?php

use App\Models\Company;
use App\Models\Module;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\Role;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Database\Migrations\Migration;

/**
 * Register FSMCore permissions and grant them to every company's admin role.
 *
 * Permissions created:
 *   - view_fsm_orders       (view FSM job orders)
 *   - manage_fsm_orders     (create / edit / delete FSM job orders)
 *   - view_fsm_locations    (view service locations)
 *   - manage_fsm_locations  (create / edit / delete locations)
 */
return new class extends Migration
{
    /** @var array<array{name:string,display_name:string}> */
    private array $permissions = [
        ['name' => 'view_fsm_orders',      'display_name' => 'View FSM Orders'],
        ['name' => 'manage_fsm_orders',    'display_name' => 'Manage FSM Orders'],
        ['name' => 'view_fsm_locations',   'display_name' => 'View FSM Locations'],
        ['name' => 'manage_fsm_locations', 'display_name' => 'Manage FSM Locations'],
    ];

    public function up(): void
    {
        // Ensure the fsmcore module record exists in the modules table.
        $module = Module::firstOrCreate(['module_name' => 'fsmcore'], [
            'module_name' => 'fsmcore',
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

            // Grant "All" access to every company's admin role.
            Company::withoutGlobalScopes()->get()->each(function (Company $company) use ($permission) {
                $role = Role::where('name', 'admin')
                    ->where('company_id', $company->id)
                    ->first();

                if ($role) {
                    PermissionRole::firstOrCreate(
                        ['permission_id' => $permission->id, 'role_id' => $role->id],
                        ['permission_type_id' => 4]  // 4 = All
                    );
                }
            });

            // Grant "All" access to every super-admin user.
            User::allAdmins()->each(function (User $admin) use ($permission) {
                UserPermission::firstOrCreate(
                    ['user_id' => $admin->id, 'permission_id' => $permission->id],
                    ['permission_type_id' => 4]  // 4 = All
                );
            });
        }
    }

    public function down(): void
    {
        $module = Module::where('module_name', 'fsmcore')->first();

        if (! $module) {
            return;
        }

        foreach ($this->permissions as $def) {
            $permission = Permission::where('name', $def['name'])
                ->where('module_id', $module->id)
                ->first();

            if ($permission) {
                PermissionRole::where('permission_id', $permission->id)->delete();
                UserPermission::where('permission_id', $permission->id)->delete();
                $permission->delete();
            }
        }
    }
};
