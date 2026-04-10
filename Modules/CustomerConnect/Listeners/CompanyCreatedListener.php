<?php

namespace Modules\CustomerConnect\Listeners;

use Modules\CustomerConnect\Entities\CustomerConnectSetting;

class CompanyCreatedListener
{
    public function handle($event): void
    {
        $company = $event->company ?? null;
        if (!$company) {
            return;
        }

        CustomerConnectSetting::addModuleSetting($company);
    }
}
