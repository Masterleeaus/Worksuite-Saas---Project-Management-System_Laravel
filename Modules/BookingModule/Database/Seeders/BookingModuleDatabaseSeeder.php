<?php

namespace Modules\BookingModule\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class BookingModuleDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Model::unguard();

        $this->call([
            BookingModulePermissionSeeder::class,
        ]);
    }
}
