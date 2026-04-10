<?php

namespace Modules\TitanDocs\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TitanDocsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call(PermissionTableSeeder::class);
        $this->call(AiTemplateTableSeeder::class);
        $this->call(AiTemplateCategoryTableSeeder::class);
        $this->call(AiTemplateLanguageTableSeeder::class);
        $this->call(AiTemplatePropmtTableSeeder::class);
       

        if(module_is_active('LandingPage'))
        {
            $this->call(MarketPlaceSeederTableSeeder::class);
        }
        
        // $this->call("OthersTableSeeder");
    }
}
