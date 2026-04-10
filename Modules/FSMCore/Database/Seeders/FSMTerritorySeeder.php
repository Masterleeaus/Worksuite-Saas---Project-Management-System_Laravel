<?php

namespace Modules\FSMCore\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\FSMCore\Models\FSMTerritory;

class FSMTerritorySeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            [
                'name' => 'Default Region',
                'type' => 'region',
                'description' => 'Default top-level region',
                'active' => true,
                'children' => [
                    [
                        'name' => 'Default District',
                        'type' => 'district',
                        'active' => true,
                        'children' => [
                            [
                                'name' => 'Default Branch',
                                'type' => 'branch',
                                'active' => true,
                                'children' => [
                                    ['name' => 'Default Territory', 'type' => 'territory', 'active' => true],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($regions as $regionData) {
            $this->createTerritory($regionData, null);
        }
    }

    private function createTerritory(array $data, ?int $parentId): FSMTerritory
    {
        $children = $data['children'] ?? [];
        unset($data['children']);

        $territory = FSMTerritory::firstOrCreate(
            ['name' => $data['name'], 'type' => $data['type']],
            array_merge($data, ['parent_id' => $parentId])
        );

        foreach ($children as $child) {
            $this->createTerritory($child, $territory->id);
        }

        return $territory;
    }
}
