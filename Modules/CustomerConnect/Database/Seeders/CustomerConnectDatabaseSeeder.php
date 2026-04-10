<?php

namespace Modules\CustomerConnect\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class CustomerConnectDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Model::unguard();

        $this->call(CustomerConnectPermissionsSeeder::class);
    }
}
