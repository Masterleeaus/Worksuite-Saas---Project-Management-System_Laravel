<?php

namespace Modules\ServiceManagement\Listeners;

use App\Models\ModuleSetting;

class CompanyCreatedListener
{
    public function handle($event): void
    {
        $company = $event->company ?? null;

        if (!$company) {
            return;
        }

        foreach (['admin', 'employee', 'client'] as $type) {
            ModuleSetting::withoutGlobalScopes()->updateOrCreate(
                [
                    'company_id' => $company->id,
                    'module_name' => 'servicemanagement',
                    'type' => $type,
                ],
                [
                    'status' => 'active',
                    'is_allowed' => 1,
                ]
            );
        }
    }
}
