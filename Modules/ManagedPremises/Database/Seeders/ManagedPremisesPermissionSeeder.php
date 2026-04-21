<?php

namespace Modules\ManagedPremises\Database\Seeders;

use App\Models\Module;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class ManagedPremisesPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $module = Module::firstOrCreate(['module_name' => 'managedpremises']);
        $groups = (array) config('managedpremises.manifests.permissions', []);

        $permissions = [];
        array_walk_recursive($groups, static function ($value) use (&$permissions): void {
            if (is_string($value)) {
                $permissions[] = $value;
            }
        });

        foreach (array_unique($permissions) as $name) {
            Permission::updateOrCreate(
                ['name' => $name, 'module_id' => $module->id],
                [
                    'display_name' => ucwords(str_replace(['_', '.'], ' ', $name)),
                    'is_custom' => 1,
                    'allowed_permissions' => Permission::ALL_NONE,
                ]
            );
        }
    }
}
