<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private string $moduleName = 'servicemanagement';

    /**
     * @var array<string, string>
     */
    private array $permissions = [
        'view_services' => 'View Services',
        'add_service' => 'Add Service',
        'edit_service' => 'Edit Service',
        'delete_service' => 'Delete Service',
    ];

    public function up(): void
    {
        $moduleId = $this->registerModule();
        $this->seedPermissions($moduleId);
        $this->seedModuleSettings();
        $this->syncPackages();
        $this->ensureCompanyBoundaryColumns();
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
            $payload['description'] = 'Service management module';
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
            } else {
                DB::table('permissions')->insert(array_merge($payload, ['created_at' => now()]));
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
            [$status, $isAllowed] = $this->moduleAccessByCompany($companyId);

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
                    'status' => $status,
                    'type' => $type,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (in_array('company_id', $columns, true)) {
                    $insert['company_id'] = $companyId;
                }

                if (in_array('is_allowed', $columns, true)) {
                    $insert['is_allowed'] = $isAllowed ? 1 : 0;
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

                $normalized = collect($modules)
                    ->filter(fn ($module) => is_string($module) && $module !== '')
                    ->map(fn (string $module) => strtolower($module))
                    ->values()
                    ->all();

                if (!in_array($this->moduleName, $normalized, true)) {
                    $normalized[] = $this->moduleName;
                }

                DB::table('packages')->where('id', $package->id)->update([
                    'module_in_package' => json_encode(array_values(array_unique($normalized))),
                ]);
            }
        });
    }

    private function moduleAccessByCompany(?int $companyId): array
    {
        if (!$companyId || !Schema::hasTable('companies') || !Schema::hasTable('packages')) {
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
            ->filter(fn ($value) => is_string($value) && $value !== '')
            ->map(fn (string $value) => strtolower($value))
            ->contains($this->moduleName);

        return [$isAllowed ? 'active' : 'deactive', $isAllowed];
    }

    private function ensureCompanyBoundaryColumns(): void
    {
        if (Schema::hasTable('service_addons') && !Schema::hasColumn('service_addons', 'company_id')) {
            Schema::table('service_addons', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index()->after('service_id');
            });
        }

        if (Schema::hasTable('service_pricing_rules') && !Schema::hasColumn('service_pricing_rules', 'company_id')) {
            Schema::table('service_pricing_rules', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index()->after('service_id');
            });
        }

        if (Schema::hasTable('services') && Schema::hasColumn('services', 'company_id')) {
            if (Schema::hasTable('service_addons') && Schema::hasColumn('service_addons', 'company_id')) {
                DB::table('service_addons')
                    ->whereNull('company_id')
                    ->orderBy('id')
                    ->chunkById(200, function ($addons) {
                        $serviceIds = $addons->pluck('service_id')->filter()->unique()->values();
                        if ($serviceIds->isEmpty()) {
                            return;
                        }

                        $serviceCompanyMap = DB::table('services')
                            ->whereIn('id', $serviceIds)
                            ->whereNotNull('company_id')
                            ->pluck('company_id', 'id');

                        foreach ($addons as $addon) {
                            $companyId = $serviceCompanyMap[$addon->service_id] ?? null;
                            if ($companyId) {
                                DB::table('service_addons')->where('id', $addon->id)->update(['company_id' => $companyId]);
                            }
                        }
                    });
            }

            if (Schema::hasTable('service_pricing_rules') && Schema::hasColumn('service_pricing_rules', 'company_id')) {
                DB::table('service_pricing_rules')
                    ->whereNull('company_id')
                    ->orderBy('id')
                    ->chunkById(200, function ($rules) {
                        $serviceIds = $rules->pluck('service_id')->filter()->unique()->values();
                        if ($serviceIds->isEmpty()) {
                            return;
                        }

                        $serviceCompanyMap = DB::table('services')
                            ->whereIn('id', $serviceIds)
                            ->whereNotNull('company_id')
                            ->pluck('company_id', 'id');

                        foreach ($rules as $rule) {
                            $companyId = $serviceCompanyMap[$rule->service_id] ?? null;
                            if ($companyId) {
                                DB::table('service_pricing_rules')->where('id', $rule->id)->update(['company_id' => $companyId]);
                            }
                        }
                    });
            }
        }
    }
};
