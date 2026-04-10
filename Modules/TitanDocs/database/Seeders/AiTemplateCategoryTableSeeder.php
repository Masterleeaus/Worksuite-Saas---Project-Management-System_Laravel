<?php

namespace Modules\TitanDocs\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\TitanDocs\Entities\AiTemplateCategory;
class AiTemplateCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $templates = [
            ['id' => 1, 'name' => 'content','status'=>true],
            ['id' => 2, 'name' => 'blog','status'=>true],
            ['id' => 3, 'name' => 'Website','status'=>true],
            ['id' => 4, 'name' => 'Social Media','status'=>true],
            ['id' => 5, 'name' => 'Video','status'=>true],
            ['id' => 6, 'name' => 'email','status'=>true],
            ['id' => 7, 'name' => 'other','status'=>true],
            

        ];

        foreach ($templates as $template) {
            AiTemplateCategory::updateOrCreate(['id' => $template['id']], $template);
        }
        // $this->call("OthersTableSeeder");
    }
}
