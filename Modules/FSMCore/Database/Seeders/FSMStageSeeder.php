<?php

namespace Modules\FSMCore\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\FSMCore\Models\FSMStage;

class FSMStageSeeder extends Seeder
{
    public function run(): void
    {
        $stages = [
            ['name' => 'New',         'sequence' => 1,  'is_completion_stage' => false, 'color' => '#6c757d'],
            ['name' => 'Assigned',    'sequence' => 2,  'is_completion_stage' => false, 'color' => '#17a2b8'],
            ['name' => 'In Progress', 'sequence' => 3,  'is_completion_stage' => false, 'color' => '#ffc107'],
            ['name' => 'Completed',   'sequence' => 4,  'is_completion_stage' => true,  'color' => '#28a745'],
            ['name' => 'Invoiced',    'sequence' => 5,  'is_completion_stage' => false, 'color' => '#007bff'],
        ];

        foreach ($stages as $stage) {
            FSMStage::firstOrCreate(['name' => $stage['name']], $stage);
        }
    }
}
