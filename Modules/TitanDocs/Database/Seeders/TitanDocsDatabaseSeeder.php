<?php

namespace Modules\TitanDocs\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class TitanDocsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Model::unguard();
        $this->call(PermissionTableSeeder::class);
        $this->call(AiTemplateTableSeeder::class);
        $this->call(AiTemplateCategoryTableSeeder::class);
        $this->call(AiTemplateLanguageTableSeeder::class);
        $this->call(AiTemplatePropmtTableSeeder::class);

        if (module_is_active('LandingPage')) {
            $this->call(MarketPlaceSeederTableSeeder::class);
        }
    }
}
