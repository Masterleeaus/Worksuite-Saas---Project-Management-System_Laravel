<?php

namespace Modules\CustomerConnect\Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    public function run(): void
    {
        // Backwards-compat seeder name from legacy Newsletter module.
        $this->call(CustomerConnectPermissionsSeeder::class);
    }
}
