<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    private const DEFAULT_ALL_PERMISSION_TYPE_ID = 4;

    public function up(): void
    {
        if (!Schema::hasTable('permissions')) {
            return;
        }

        $hasModuleColumn = Schema::hasColumn('permissions', 'module');
        $hasModuleIdColumn = Schema::hasColumn('permissions', 'module_id');
        $moduleId = null;

        if ($hasModuleIdColumn && Schema::hasTable('modules') && Schema::hasColumn('modules', 'module_name')) {
            $module = DB::table('modules')
                ->whereIn('module_name', ['titandocs', 'TitanDocs'])
                ->first();

            if (!$module) {
                $moduleId = DB::table('modules')->insertGetId([
                    'module_name' => 'titandocs',
                    'description' => 'TitanDocs',
                    'is_superadmin' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $moduleId = $module->id;
            }
        }

        $canAssociatePermission = $hasModuleColumn || ($hasModuleIdColumn && $moduleId !== null);

        if (!$canAssociatePermission) {
            return;
        }

        $permissions = [
            'view_documents',
            'create_documents',
            'generate_documents',
            'delete_documents',
            'manage_templates',
        ];

        $applyPermissionModuleScope = function ($query) use ($hasModuleColumn, $hasModuleIdColumn, $moduleId) {
            if ($hasModuleColumn) {
                $query->where('module', 'TitanDocs');
            } elseif ($hasModuleIdColumn && $moduleId !== null) {
                $query->where('module_id', $moduleId);
            }

            return $query;
        };

        foreach ($permissions as $perm) {
            $exists = $applyPermissionModuleScope(
                DB::table('permissions')->where('name', $perm)
            )->exists();

            if (!$exists) {
                $row = [
                    'name' => $perm,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (Schema::hasColumn('permissions', 'guard_name')) {
                    $row['guard_name'] = 'web';
                }

                if ($hasModuleColumn) {
                    $row['module'] = 'TitanDocs';
                } elseif ($hasModuleIdColumn && $moduleId !== null) {
                    $row['module_id'] = $moduleId;
                }

                if (Schema::hasColumn('permissions', 'created_by')) {
                    $row['created_by'] = 0;
                }

                DB::table('permissions')->insert($row);
            }
        }

        // Give company role basic document permissions
        $pivotTable = null;
        if (Schema::hasTable('permission_role')) {
            $pivotTable = 'permission_role';
        } elseif (Schema::hasTable('role_has_permissions')) {
            $pivotTable = 'role_has_permissions';
        }

        if (Schema::hasTable('roles') && $pivotTable) {
            $allPermissionTypeId = self::DEFAULT_ALL_PERMISSION_TYPE_ID;
            if (
                $pivotTable === 'permission_role'
                && Schema::hasTable('permission_types')
                && Schema::hasColumn('permission_types', 'name')
            ) {
                $allPermissionTypeId = DB::table('permission_types')->where('name', 'all')->value('id')
                    ?? self::DEFAULT_ALL_PERMISSION_TYPE_ID;
            }

            $companyRole = DB::table('roles')->where('name', 'company')->first();
            if ($companyRole) {
                $basicPerms = ['view_documents', 'create_documents', 'generate_documents', 'delete_documents', 'manage_templates'];
                foreach ($basicPerms as $permName) {
                    $permission = $applyPermissionModuleScope(
                        DB::table('permissions')->where('name', $permName)
                    )->first();

                    if ($permission) {
                        $alreadyHas = DB::table($pivotTable)
                            ->where('permission_id', $permission->id)
                            ->where('role_id', $companyRole->id)
                            ->exists();

                        if (!$alreadyHas) {
                            $pivotData = [
                                'permission_id' => $permission->id,
                                'role_id' => $companyRole->id,
                            ];

                            if ($pivotTable === 'permission_role' && Schema::hasColumn('permission_role', 'permission_type_id')) {
                                $pivotData['permission_type_id'] = $allPermissionTypeId;
                            }

                            DB::table($pivotTable)->insert($pivotData);
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
