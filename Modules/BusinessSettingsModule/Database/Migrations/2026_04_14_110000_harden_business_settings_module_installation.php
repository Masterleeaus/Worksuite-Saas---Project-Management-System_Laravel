<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private string $moduleName = 'businesssettingsmodule';

    /**
     * @var array<string,string>
     */
    private array $permissions = [
        'manage_business_settings' => 'Manage Business Settings',
        'manage_subscription' => 'Manage Subscription',
        'view_package_features' => 'View Package Features',
        'manage_packages' => 'Manage Packages',
    ];

    public function up(): void
    {
        $moduleId = $this->registerModule();
        $this->seedPermissions($moduleId);
        $this->seedModuleSettings();
        $this->syncPackages();
    }

    public function down(): void
    {
        if (Schema::hasTable('permissions')) {
            DB::table('permissions')->whereIn('name', array_keys($this->permissions))->delete();
        }

        if (Schema::hasTable('module_settings')) {
            DB::table('module_settings')->where('module_name', $this->moduleName)->delete();
        }

        if (Schema::hasTable('modules')) {
            DB::table('modules')->where('module_name', $this->moduleName)->delete();
        }
    }

    private function registerModule(): ?int
    {
        if (!Schema::hasTable('modules')) {
            return null;
        }

        $columns = Schema::getColumnListing('modules');
        $payload = ['module_name' => $this->moduleName];

        if (in_array('description', $columns, true)) {
            $payload['description'] = 'Business settings and subscriptions';
        }

        if (in_array('is_superadmin', $columns, true)) {
            $payload['is_superadmin'] = 0;
        }

        $existing = DB::table('modules')->where('module_name', $this->moduleName)->first();

        if ($existing) {
            DB::table('modules')->where('id', $existing->id)->update(array_merge($payload, ['updated_at' => now()]));
            return (int) $existing->id;
        }

        return (int) DB::table('modules')->insertGetId(array_merge($payload, [
            'created_at' => now(),
            'updated_at' => now(),
        ]));
    }

    private function seedPermissions(?int $moduleId): void
    {
        if (empty($moduleId) || !Schema::hasTable('permissions')) {
            return;
        }

        foreach ($this->permissions as $name => $displayName) {
            $payload = [
                'name' => $name,
                'module_id' => $moduleId,
                'display_name' => $displayName,
                'allowed_permissions' => '{"all":4, "none":5}',
                'is_custom' => 1,
                'updated_at' => now(),
            ];

            $existing = DB::table('permissions')
                ->where('name', $name)
                ->where('module_id', $moduleId)
                ->first();

            if ($existing) {
                DB::table('permissions')->where('id', $existing->id)->update($payload);
                $permissionId = (int) $existing->id;
            } else {
                $permissionId = (int) DB::table('permissions')->insertGetId(array_merge($payload, ['created_at' => now()]));
            }

            $this->attachPermissionToRoles($permissionId);
        }
    }

    private function attachPermissionToRoles(int $permissionId): void
    {
        if (!Schema::hasTable('permission_role') || !Schema::hasTable('roles')) {
            return;
        }

        $roleIds = DB::table('roles')
            ->whereIn('name', ['admin', 'superadmin', 'super_admin'])
            ->pluck('id');

        foreach ($roleIds as $roleId) {
            $exists = DB::table('permission_role')
                ->where('permission_id', $permissionId)
                ->where('role_id', $roleId)
                ->exists();

            if (!$exists) {
                DB::table('permission_role')->insert([
                    'permission_id' => $permissionId,
                    'role_id' => $roleId,
                    'permission_type_id' => 4,
                ]);
            }
        }
    }

    private function seedModuleSettings(): void
    {
        if (!Schema::hasTable('module_settings')) {
            return;
        }

        $types = ['admin', 'employee', 'client'];
        $columns = Schema::getColumnListing('module_settings');
        $companyIds = [null];

        if (in_array('company_id', $columns, true) && Schema::hasTable('companies')) {
            $companyIds = DB::table('companies')->pluck('id')->map(fn ($id) => (int) $id)->all();
            if (empty($companyIds)) {
                $companyIds = [null];
            }
        }

        foreach ($companyIds as $companyId) {
            foreach ($types as $type) {
                $query = DB::table('module_settings')
                    ->where('module_name', $this->moduleName)
                    ->where('type', $type);

                if (in_array('company_id', $columns, true)) {
                    $companyId === null ? $query->whereNull('company_id') : $query->where('company_id', $companyId);
                }

                if ($query->exists()) {
                    continue;
                }

                $insert = [
                    'module_name' => $this->moduleName,
                    'status' => 'active',
                    'type' => $type,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (in_array('company_id', $columns, true)) {
                    $insert['company_id'] = $companyId;
                }

                if (in_array('is_allowed', $columns, true)) {
                    $insert['is_allowed'] = 1;
                }

                DB::table('module_settings')->insert($insert);
            }
        }
    }

    private function syncPackages(): void
    {
        if (!Schema::hasTable('packages')) {
            return;
        }

        $columns = Schema::getColumnListing('packages');
        if (!in_array('module_in_package', $columns, true)) {
            return;
        }

        DB::table('packages')->orderBy('id')->chunkById(50, function ($packages) {
            foreach ($packages as $package) {
                $modules = json_decode((string) $package->module_in_package, true);
                if (!is_array($modules)) {
                    $modules = [];
                }

                if (in_array($this->moduleName, $modules, true)) {
                    continue;
                }

                $modules[] = $this->moduleName;
                DB::table('packages')->where('id', $package->id)->update([
                    'module_in_package' => json_encode(array_values(array_unique($modules))),
                ]);
            }
        });
    }
};

