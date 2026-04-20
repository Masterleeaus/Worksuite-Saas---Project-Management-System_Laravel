<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $modules = $this->discoverFsmModules();

        $this->syncModuleRegistry($modules);
        $this->syncPackageEntitlements($modules);
        $this->seedModuleSettings($modules);
        $this->seedPermissions($modules);
    }

    public function down(): void
    {
        // Intentionally keep module registry/settings records to avoid destructive tenant-impacting rollbacks.
    }

    /**
     * @return array<int, array{name: string, alias: string, description: string}>
     */
    private function discoverFsmModules(): array
    {
        $manifests = glob(base_path('Modules/FSM*/module.json')) ?: [];
        $modules = [];

        foreach ($manifests as $manifestPath) {
            $manifest = json_decode((string) file_get_contents($manifestPath), true);

            if (!is_array($manifest)) {
                continue;
            }

            $name = strtolower((string) ($manifest['alias'] ?? basename(dirname($manifestPath))));
            if ($name === '') {
                continue;
            }

            $modules[] = [
                'name' => $name,
                'alias' => (string) ($manifest['alias'] ?? ''),
                'description' => (string) ($manifest['description'] ?? ''),
            ];
        }

        return collect($modules)->unique('name')->values()->all();
    }

    /**
     * @param array<int, array{name: string, alias: string, description: string}> $modules
     */
    private function syncModuleRegistry(array $modules): void
    {
        if (!Schema::hasTable('modules')) {
            return;
        }

        $moduleColumns = Schema::getColumnListing('modules');
        $hasDescription = in_array('description', $moduleColumns, true);
        $hasSuperAdmin = in_array('is_superadmin', $moduleColumns, true);
        $hasCreatedAt = in_array('created_at', $moduleColumns, true);
        $hasUpdatedAt = in_array('updated_at', $moduleColumns, true);

        foreach ($modules as $module) {
            $payload = ['module_name' => $module['name']];

            if ($hasDescription && $module['description'] !== '') {
                $payload['description'] = $module['description'];
            }

            if ($hasSuperAdmin) {
                $payload['is_superadmin'] = 0;
            }

            if ($hasUpdatedAt) {
                $payload['updated_at'] = now();
            }

            $existing = DB::table('modules')->where('module_name', $module['name'])->first();

            if ($existing) {
                DB::table('modules')->where('id', $existing->id)->update($payload);
                continue;
            }

            if ($hasCreatedAt) {
                $payload['created_at'] = now();
            }

            DB::table('modules')->insert($payload);
        }
    }

    /**
     * @param array<int, array{name: string, alias: string, description: string}> $modules
     */
    private function seedModuleSettings(array $modules): void
    {
        if (!Schema::hasTable('module_settings')) {
            return;
        }

        $columns = Schema::getColumnListing('module_settings');
        $hasCompanyId = in_array('company_id', $columns, true);
        $hasIsAllowed = in_array('is_allowed', $columns, true);
        $hasCreatedAt = in_array('created_at', $columns, true);
        $hasUpdatedAt = in_array('updated_at', $columns, true);

        $companyIds = [null];
        if ($hasCompanyId && Schema::hasTable('companies')) {
            $companyIds = DB::table('companies')->pluck('id')->map(static fn ($id) => (int) $id)->all();
            if (empty($companyIds)) {
                $companyIds = [null];
            }
        }

        foreach ($modules as $module) {
            foreach ($companyIds as $companyId) {
                [$status, $isAllowed] = $this->resolveTenantModuleAccess($module['name'], $companyId);

                foreach (['admin', 'employee', 'client'] as $type) {
                    $query = DB::table('module_settings')
                        ->where('module_name', $module['name'])
                        ->where('type', $type);

                    if ($hasCompanyId) {
                        $companyId === null ? $query->whereNull('company_id') : $query->where('company_id', $companyId);
                    }

                    if ($query->exists()) {
                        continue;
                    }

                    $insert = [
                        'module_name' => $module['name'],
                        'status' => $status,
                        'type' => $type,
                    ];

                    if ($hasCompanyId) {
                        $insert['company_id'] = $companyId;
                    }

                    if ($hasIsAllowed) {
                        $insert['is_allowed'] = $isAllowed ? 1 : 0;
                    }

                    if ($hasCreatedAt) {
                        $insert['created_at'] = now();
                    }

                    if ($hasUpdatedAt) {
                        $insert['updated_at'] = now();
                    }

                    DB::table('module_settings')->insert($insert);
                }
            }
        }
    }

    /**
     * @param array<int, array{name: string, alias: string, description: string}> $modules
     */
    private function syncPackageEntitlements(array $modules): void
    {
        if (!Schema::hasTable('packages')) {
            return;
        }

        $packageColumns = Schema::getColumnListing('packages');

        if (!in_array('module_in_package', $packageColumns, true)) {
            return;
        }

        $fsmModuleNames = collect($modules)->pluck('name')->filter()->unique()->values()->all();
        if (empty($fsmModuleNames)) {
            return;
        }

        DB::table('packages')->orderBy('id')->chunkById(100, function ($packages) use ($fsmModuleNames) {
            foreach ($packages as $package) {
                $rawModules = json_decode((string) $package->module_in_package, true);
                $normalized = collect(is_array($rawModules) ? $rawModules : [])
                    ->filter(static fn ($value) => is_string($value) && trim($value) !== '')
                    ->map(static fn (string $value) => strtolower(trim($value)))
                    ->unique()
                    ->values();

                $hasFsmSuite = $normalized->contains(static fn (string $value) => str_starts_with($value, 'fsm'));
                if (!$hasFsmSuite) {
                    continue;
                }

                $next = $normalized->merge($fsmModuleNames)->unique()->values()->all();

                if ($next === $normalized->all()) {
                    continue;
                }

                DB::table('packages')->where('id', $package->id)->update([
                    'module_in_package' => json_encode($next),
                ]);
            }
        });
    }

    /**
     * @param array<int, array{name: string, alias: string, description: string}> $modules
     */
    private function seedPermissions(array $modules): void
    {
        if (!Schema::hasTable('permissions')) {
            return;
        }

        $permissionColumns = Schema::getColumnListing('permissions');
        $hasCreatedAt = in_array('created_at', $permissionColumns, true);
        $hasUpdatedAt = in_array('updated_at', $permissionColumns, true);
        $hasAllowedPermissions = in_array('allowed_permissions', $permissionColumns, true);
        $hasIsCustom = in_array('is_custom', $permissionColumns, true);

        foreach ($modules as $module) {
            $moduleRow = DB::table('modules')->where('module_name', $module['name'])->first(['id']);
            if (!$moduleRow) {
                continue;
            }

            $permissionIds = [];

            foreach ($this->defaultPermissionDefinitions($module['name']) as $permissionName => $displayName) {
                $existing = DB::table('permissions')->where('name', $permissionName)->first(['id', 'module_id']);

                if ($existing && (int) $existing->module_id !== (int) $moduleRow->id) {
                    continue;
                }

                $payload = [
                    'name' => $permissionName,
                    'module_id' => $moduleRow->id,
                    'display_name' => $displayName,
                ];

                if ($hasIsCustom) {
                    $payload['is_custom'] = 1;
                }

                if ($hasAllowedPermissions) {
                    $payload['allowed_permissions'] = json_encode(['all' => 4, 'none' => 5]);
                }

                if ($hasUpdatedAt) {
                    $payload['updated_at'] = now();
                }

                if ($existing) {
                    DB::table('permissions')->where('id', $existing->id)->update($payload);
                    $permissionIds[] = (int) $existing->id;
                    continue;
                }

                if ($hasCreatedAt) {
                    $payload['created_at'] = now();
                }

                $permissionIds[] = (int) DB::table('permissions')->insertGetId($payload);
            }

            if (!empty($permissionIds)) {
                $this->grantPermissionsToAdminRoles($permissionIds);
            }
        }
    }

    /**
     * @param array<int> $permissionIds
     */
    private function grantPermissionsToAdminRoles(array $permissionIds): void
    {
        if (empty($permissionIds) || !Schema::hasTable('roles')) {
            return;
        }

        $roleColumns = Schema::getColumnListing('roles');
        if (!in_array('name', $roleColumns, true)) {
            return;
        }

        $adminRoles = DB::table('roles')->where('name', 'admin')->get(['id']);
        if ($adminRoles->isEmpty()) {
            return;
        }

        if (Schema::hasTable('permission_role')) {
            foreach ($adminRoles as $role) {
                foreach ($permissionIds as $permissionId) {
                    $exists = DB::table('permission_role')
                        ->where('permission_id', $permissionId)
                        ->where('role_id', $role->id)
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    DB::table('permission_role')->insert([
                        'permission_id' => $permissionId,
                        'role_id' => $role->id,
                        'permission_type_id' => 4,
                    ]);
                }
            }
        }

        if (Schema::hasTable('user_permissions') && Schema::hasTable('users')) {
            $userColumns = Schema::getColumnListing('users');
            $userQuery = DB::table('users');

            if (in_array('is_superadmin', $userColumns, true)) {
                $userQuery->where('is_superadmin', 1);
            }

            $users = $userQuery->get(['id']);

            foreach ($users as $user) {
                foreach ($permissionIds as $permissionId) {
                    $exists = DB::table('user_permissions')
                        ->where('user_id', $user->id)
                        ->where('permission_id', $permissionId)
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    DB::table('user_permissions')->insert([
                        'user_id' => $user->id,
                        'permission_id' => $permissionId,
                        'permission_type_id' => 4,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * @return array<string, string>
     */
    private function defaultPermissionDefinitions(string $moduleName): array
    {
        $label = strtoupper($moduleName);

        return [
            'view_' . $moduleName => "View {$label}",
            'manage_' . $moduleName => "Manage {$label}",
        ];
    }

    /**
     * @return array{0: string, 1: bool}
     */
    private function resolveTenantModuleAccess(string $moduleName, ?int $companyId): array
    {
        if (!$companyId || !Schema::hasTable('companies') || !Schema::hasTable('packages')) {
            return ['active', true];
        }

        if (!in_array('package_id', Schema::getColumnListing('companies'), true)) {
            return ['active', true];
        }

        if (!in_array('module_in_package', Schema::getColumnListing('packages'), true)) {
            return ['active', true];
        }

        $company = DB::table('companies')->where('id', $companyId)->first(['package_id']);
        if (!$company || empty($company->package_id)) {
            return ['active', true];
        }

        $package = DB::table('packages')->where('id', $company->package_id)->first(['module_in_package']);
        $modules = json_decode((string) data_get($package, 'module_in_package'), true);

        if (!is_array($modules)) {
            return ['active', true];
        }

        $isAllowed = collect($modules)
            ->filter(static fn ($value) => is_string($value) && $value !== '')
            ->map(static fn (string $value) => strtolower($value))
            ->contains($moduleName);

        return [$isAllowed ? 'active' : 'deactive', $isAllowed];
    }
};
