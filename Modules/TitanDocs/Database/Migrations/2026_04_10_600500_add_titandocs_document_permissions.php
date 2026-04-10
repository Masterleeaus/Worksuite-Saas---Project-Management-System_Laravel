<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('permissions')) {
            return;
        }

        $permissions = [
            'view_documents',
            'create_documents',
            'generate_documents',
            'delete_documents',
            'manage_templates',
        ];

        foreach ($permissions as $perm) {
            $exists = DB::table('permissions')
                ->where('name', $perm)
                ->where('module', 'TitanDocs')
                ->exists();
            if (!$exists) {
                DB::table('permissions')->insert([
                    'name' => $perm,
                    'guard_name' => 'web',
                    'module' => 'TitanDocs',
                    'created_by' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Give company role basic document permissions
        if (Schema::hasTable('roles') && Schema::hasTable('role_has_permissions')) {
            $companyRole = DB::table('roles')->where('name', 'company')->first();
            if ($companyRole) {
                $basicPerms = ['view_documents', 'create_documents', 'generate_documents', 'delete_documents', 'manage_templates'];
                foreach ($basicPerms as $permName) {
                    $permission = DB::table('permissions')->where('name', $permName)->where('module','TitanDocs')->first();
                    if ($permission) {
                        $alreadyHas = DB::table('role_has_permissions')
                            ->where('permission_id', $permission->id)
                            ->where('role_id', $companyRole->id)
                            ->exists();
                        if (!$alreadyHas) {
                            DB::table('role_has_permissions')->insert([
                                'permission_id' => $permission->id,
                                'role_id' => $companyRole->id,
                            ]);
                        }
                    }
                }
            }
        }
    }

    public function down(): void
    {
        // non-destructive
    }
};
