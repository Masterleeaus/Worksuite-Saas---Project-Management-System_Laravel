<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        try {
            if (!class_exists(\App\Models\Permission::class)) {
                return;
            }

            $newPermissions = [
                'view_inspections',
                'create_inspection',
                'approve_inspection',
                'request_reclean',
            ];

            $roleModel = class_exists(\App\Models\Role::class) ? \App\Models\Role::class : null;

            foreach ($newPermissions as $name) {
                $perm = \App\Models\Permission::where('name', $name)->first();

                if (!$perm) {
                    $perm = \App\Models\Permission::create([
                        'name'       => $name,
                        'guard_name' => 'web',
                        'module'     => 'Inspection',
                        'created_by' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Grant all new permissions to admin and manager roles.
            if ($roleModel) {
                $adminRole   = $roleModel::where('name', 'admin')->first();
                $managerRole = $roleModel::where('name', 'manager')->first();

                $adminPerms = $newPermissions;
                $managerPerms = ['view_inspections', 'create_inspection', 'approve_inspection'];

                foreach ([[$adminRole, $adminPerms], [$managerRole, $managerPerms]] as [$role, $perms]) {
                    if (!$role) {
                        continue;
                    }

                    foreach ($perms as $permName) {
                        $p = \App\Models\Permission::where('name', $permName)->first();

                        if ($p && method_exists($role, 'hasPermission') && method_exists($role, 'givePermission')) {
                            if (!$role->hasPermission($permName)) {
                                $role->givePermission($p);
                            }
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            // Never break global migrations because of a permission seed failure.
        }
    }

    public function down(): void
    {
        // Non-destructive.
    }
};
