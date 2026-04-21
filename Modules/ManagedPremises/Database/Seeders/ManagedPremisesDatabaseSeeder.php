<?php

namespace Modules\ManagedPremises\Database\Seeders;

use Illuminate\Database\Seeder;

class ManagedPremisesDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ManagedPremisesPermissionSeeder::class,
            ManagedPremisesSettingsSeeder::class,
            ManagedPremisesStatusSeeder::class,
            ManagedPremisesMenuSeeder::class,
            DemoManagedPremisesSeeder::class,
        ]);
    }
}
