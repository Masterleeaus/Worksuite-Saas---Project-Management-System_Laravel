<?php

namespace Modules\ZeroPay\Database\Seeders;

use Illuminate\Database\Seeder;

class ZeroPayDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ZeroPayPermissionSeeder::class,
            ZeroPaySettingsSeeder::class,
        ]);
    }
}
