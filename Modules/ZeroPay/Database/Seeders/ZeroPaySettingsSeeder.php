<?php

namespace Modules\ZeroPay\Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Modules\ZeroPay\Models\ZeroPaySetting;

class ZeroPaySettingsSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('companies') || !Schema::hasTable('zeropay_settings')) {
            return;
        }

        $defaults = (array) config('zeropay.settings_defaults', []);

        Company::query()->select('id')->chunkById(100, function ($companies) use ($defaults): void {
            foreach ($companies as $company) {
                ZeroPaySetting::query()->firstOrCreate(
                    ['company_id' => (int) $company->id],
                    ['settings' => $defaults]
                );
            }
        });
    }
}
