<?php

namespace Modules\ZeroPay\Database\Seeders;

use App\Models\Module;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ZeroPayPermissionSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('permissions') || !Schema::hasTable('modules')) {
            return;
        }

        $module = Module::query()->firstOrCreate(
            ['module_name' => 'zeropay'],
            ['description' => 'ZeroPay module permissions', 'type' => 'system', 'is_custom' => false]
        );

        foreach ((array) config('zeropay_permissions.keys', []) as $permissionKey) {
            Permission::query()->firstOrCreate(
                ['name' => $permissionKey],
                [
                    'display_name' => ucwords(str_replace('_', ' ', $permissionKey)),
                    'module_id' => $module->id,
                    'is_custom' => false,
                ]
            );
        }
    }
}
