<?php

namespace Modules\ZeroPay\Providers;

use App\Models\Company;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Modules\ZeroPay\Models\ZeroPaySetting;

class ModuleBootServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (!Schema::hasTable('companies') || !Schema::hasTable('zeropay_settings')) {
            return;
        }

        $defaults = (array) config('zeropay.settings_defaults', []);

        if ($this->app->runningInConsole()) {
            Company::query()->select('id')->chunkById(100, function ($companies) use ($defaults): void {
                foreach ($companies as $company) {
                    ZeroPaySetting::query()->firstOrCreate(
                        ['company_id' => (int) $company->id],
                        ['settings' => $defaults]
                    );
                }
            });

            return;
        }

        $companyId = auth()->user()?->company_id;

        if ($companyId) {
            ZeroPaySetting::query()->firstOrCreate(
                ['company_id' => (int) $companyId],
                ['settings' => $defaults]
            );
        }
    }
}
