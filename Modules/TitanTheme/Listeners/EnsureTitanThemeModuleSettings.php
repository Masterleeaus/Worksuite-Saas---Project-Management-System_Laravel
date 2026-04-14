<?php

namespace Modules\TitanTheme\Listeners;

use App\Models\Company;
use App\Models\ModuleSetting;
use Illuminate\Support\Facades\Schema;

class EnsureTitanThemeModuleSettings
{
    public function handle(Company $company): void
    {
        if (!$company->id || !Schema::hasTable('module_settings')) {
            return;
        }

        if (!$company->package_id || !$company->package) {
            return;
        }

        ModuleSetting::createRoleSettingEntry('titantheme', ['admin', 'employee', 'client'], $company);
    }
}
