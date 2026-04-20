<?php

namespace Modules\ZeroPay\Support\Helpers;

use Modules\ZeroPay\Models\ZeroPaySetting;

class ZeroPaySettings
{
    public static function resolve(?int $companyId): array
    {
        $defaults = (array) config('zeropay.settings_defaults', []);

        if (!$companyId) {
            return $defaults;
        }

        $saved = ZeroPaySetting::getCompanySettings($companyId);

        return array_replace_recursive($defaults, $saved);
    }
}
