<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        // Ensure the module's permissions exist in the main `permissions` table
        // used by menu visibility checks in Worksuite.
        try {
            if (!class_exists(\App\Models\Permission::class)) {
                return;
            }

            $permModel = \App\Models\Permission::class;
            $roleModel = class_exists(\App\Models\Role::class) ? \App\Models\Role::class : null;

            $permissions = [
                'add_inspection',
                'view_inspection',
                'edit_inspection',
                'delete_inspection',
            ];

            $companyRole = $roleModel ? $roleModel::where('name', 'company')->first() : null;

            foreach ($permissions as $name) {
                $perm = $permModel::where('name', $name)
                    ->where('module', 'Inspection')
                    ->first();

                if (!$perm) {
                    $perm = $permModel::create([
                        'name' => $name,
                        'guard_name' => 'web',
                        'module' => 'Inspection',
                        'created_by' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Attach to company role if helper methods exist.
                if ($companyRole && method_exists($companyRole, 'hasPermission') && method_exists($companyRole, 'givePermission')) {
                    if (!$companyRole->hasPermission($name)) {
                        $companyRole->givePermission($perm);
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
