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
 * Register FSMServiceAgreement permissions and grant them to every company's admin role.
 *
 * Permissions created:
 *   - view_fsm_agreements    (view service agreements / contracts)
 *   - manage_fsm_agreements  (create / edit / delete service agreements)
 */
return new class extends Migration
{
    /** @var array<array{name:string,display_name:string}> */
    private array $permissions = [
        ['name' => 'view_fsm_agreements',   'display_name' => 'View FSM Service Agreements'],
        ['name' => 'manage_fsm_agreements', 'display_name' => 'Manage FSM Service Agreements'],
    ];

    public function up(): void
    {
        // Ensure the fsmserviceagreement module record exists.
        $module = Module::firstOrCreate(['module_name' => 'fsmserviceagreement'], [
            'module_name' => 'fsmserviceagreement',
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
        $module = Module::where('module_name', 'fsmserviceagreement')->first();

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
