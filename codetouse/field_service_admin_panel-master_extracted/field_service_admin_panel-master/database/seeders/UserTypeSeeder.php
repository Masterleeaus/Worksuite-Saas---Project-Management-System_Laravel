<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   UserType::create(["type"=>"admin"]);
        UserType::create(["type"=>"sub_admin"]);
        UserType::create(["type"=>"technician"]);
        UserType::create(["type"=>"sales_person"]);
    }
}
