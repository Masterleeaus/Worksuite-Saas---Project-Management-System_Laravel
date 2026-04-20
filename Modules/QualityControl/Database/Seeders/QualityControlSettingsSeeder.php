<?php

namespace Modules\QualityControl\Database\Seeders;

use App\Models\Company;
use App\Models\Module;
use Illuminate\Database\Seeder;
use Modules\QualityControl\Entities\RecurringSchedule;

class QualityControlSettingsSeeder extends Seeder
{
    public function run(): void
    {
        Module::query()->firstOrCreate(
            ['module_name' => 'quality_control'],
            ['is_superadmin' => 0, 'description' => 'Quality Control module']
        );

        if (!class_exists(Company::class)) {
            return;
        }

        Company::query()->select('id')->chunkById(100, function ($companies) {
            foreach ($companies as $company) {
                RecurringSchedule::addModuleSetting($company);
            }
        });
    }
}
