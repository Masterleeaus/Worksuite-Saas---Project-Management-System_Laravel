<?php

namespace Modules\FSMCore\Database\Seeders;

use Illuminate\Database\Seeder;

class FSMCoreDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            FSMStageSeeder::class,
            FSMTerritorySeeder::class,
        ]);
    }
}
