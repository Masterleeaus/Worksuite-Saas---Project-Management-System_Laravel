<?php

namespace App\Listeners\SuperAdmin;

use App\Events\NewCompanyCreatedEvent;
use App\Models\Module;
use App\Models\ModuleSetting;

class CompanyModuleSettingsListener
{
    /**
     * Seed module_settings rows for the newly created company so the
     * Module Registry installer can track status per company.
     */
    public function handle(NewCompanyCreatedEvent $event): void
    {
        $company = $event->company;

        if (! $company || ! $company->id) {
            return;
        }

        // Collect all non-superadmin module names from the modules table.
        $modules = Module::withoutGlobalScopes()
            ->where('is_superadmin', 0)
            ->pluck('module_name');

        foreach ($modules as $moduleName) {
            ModuleSetting::firstOrCreate(
                [
                    'company_id'  => $company->id,
                    'module_name' => $moduleName,
                ],
                [
                    'status' => 'active',
                    'type'   => 'admin',
                ]
            );
        }
    }
}
