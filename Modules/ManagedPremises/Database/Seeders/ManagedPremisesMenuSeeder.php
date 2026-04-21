<?php

namespace Modules\ManagedPremises\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ManagedPremisesMenuSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('menus')) {
            return;
        }

        $columns = Schema::getColumnListing('menus');
        $hasKey = in_array('key', $columns, true);

        $groupPayload = [
            'name' => 'Managed Premises',
            'label' => 'Managed Premises',
            'route' => '#',
            'type' => 'group',
            'order' => 55,
            'is_active' => 1,
            'module' => 'managedpremises',
            'module_name' => 'managedpremises',
            'updated_at' => now(),
            'created_at' => now(),
        ];

        if ($hasKey) {
            $groupPayload['key'] = 'managedpremises';
            $group = DB::table('menus')->where('key', 'managedpremises')->first();
        } else {
            $group = DB::table('menus')->where('name', 'Managed Premises')->first();
        }

        if ($group) {
            DB::table('menus')->where('id', $group->id)->update($this->filterColumns($columns, $groupPayload));
            $groupId = $group->id;
        } else {
            $groupId = DB::table('menus')->insertGetId($this->filterColumns($columns, $groupPayload));
        }

        foreach ((array) config('managedpremises.manifests.menus', []) as $index => $menu) {
            if (!is_array($menu)) {
                continue;
            }

            $itemPayload = [
                'parent_id' => $groupId,
                'name' => $menu['label'] ?? 'Menu',
                'label' => $menu['label'] ?? 'Menu',
                'route' => $menu['route'] ?? '#',
                'type' => 'item',
                'order' => $index + 1,
                'is_active' => 1,
                'module' => 'managedpremises',
                'module_name' => 'managedpremises',
                'updated_at' => now(),
                'created_at' => now(),
            ];

            if ($hasKey) {
                $slug = Str::slug((string) ($menu['label'] ?? ''));
                $itemPayload['key'] = 'managedpremises.menu.' . ($slug ?: 'item-' . md5($itemPayload['route'] . $index));
            }

            $exists = DB::table('menus')
                ->when($hasKey, fn ($q) => $q->where('key', $itemPayload['key']))
                ->when(!$hasKey, fn ($q) => $q->where('name', $itemPayload['name'])->where('parent_id', $groupId))
                ->exists();

            if (!$exists) {
                DB::table('menus')->insert($this->filterColumns($columns, $itemPayload));
            }
        }
    }

    private function filterColumns(array $columns, array $payload): array
    {
        $out = [];
        foreach ($payload as $key => $value) {
            if (in_array($key, $columns, true)) {
                $out[$key] = $value;
            }
        }

        return $out;
    }
}
