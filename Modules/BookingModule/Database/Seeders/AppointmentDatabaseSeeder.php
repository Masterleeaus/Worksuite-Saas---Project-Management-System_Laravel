<?php

namespace Modules\BookingModule\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class AppointmentDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");

        $this->call(PermissionTableSeeder::class);
        $this->call(EmailTemplatesTableSeeder::class);
        $this->call(NotificationsTableSeeder::class);
    }
}
