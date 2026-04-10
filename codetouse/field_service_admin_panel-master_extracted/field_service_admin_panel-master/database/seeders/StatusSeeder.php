<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;
use NunoMaduro\Collision\Adapters\Phpunit\State;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::create(["id"=>1,"name"=>"Active","module"=>"product"]);
        Status::create(["id"=>2,"name"=>"De-Active","module"=>"product"]);
        Status::create(["id"=>3,"name"=>"Pending","module"=>"product"]);
        Status::create(["id"=>4,"name"=>"Blocked","module"=>"product"]);
       
    }
}
