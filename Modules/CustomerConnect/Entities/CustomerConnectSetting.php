<?php

namespace Modules\CustomerConnect\Entities;

use App\Models\ModuleSetting;

class CustomerConnectSetting
{
    public const MODULE_NAME = 'customerconnect';

    /**
     * Ensure module_settings entries exist for the given company.
     * Worksuite uses this to decide if module is allowed based on the assigned package.
     */
    public static function addModuleSetting($company): void
    {
        // Keep roles conservative; admins always, employees optional.
        ModuleSetting::createRoleSettingEntry(
            self::MODULE_NAME,
            ['admin', 'employee'],
            $company
        );
    }
}
