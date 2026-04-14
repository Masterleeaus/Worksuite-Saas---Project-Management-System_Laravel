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
                DB::table('service_addons as a')
                    ->join('services as s', 's.id', '=', 'a.service_id')
                    ->whereNull('a.company_id')
                    ->whereNotNull('s.company_id')
                    ->update(['company_id' => DB::raw('s.company_id')]);
            }

            if (Schema::hasTable('service_pricing_rules') && Schema::hasColumn('service_pricing_rules', 'company_id')) {
                DB::table('service_pricing_rules as r')
                    ->join('services as s', 's.id', '=', 'r.service_id')
                    ->whereNull('r.company_id')
                    ->whereNotNull('s.company_id')
                    ->update(['company_id' => DB::raw('s.company_id')]);
            }
        }
    }
};
