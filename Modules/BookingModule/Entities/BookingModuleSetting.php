<?php

namespace Modules\BookingModule\Entities;

use App\Models\ModuleSetting;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;

class BookingModuleSetting
{
    const MODULE_NAME = 'bookingmodule';

    public static function query(): Builder
    {
        return ModuleSetting::withoutGlobalScope(CompanyScope::class)
            ->where('module_name', self::MODULE_NAME);
    }

    public static function first()
    {
        return self::query()->first();
    }

    public static function addModuleSetting($company): void
    {
        $roles = ['employee', 'admin'];
        ModuleSetting::createRoleSettingEntry(self::MODULE_NAME, $roles, $company);

        ModuleSetting::withoutGlobalScope(CompanyScope::class)
            ->where('module_name', self::MODULE_NAME)
            ->where('company_id', $company->id)
            ->update([
                'status' => 'active',
                'is_allowed' => 1,
            ]);
    }

    public static function ensureActiveForCurrentCompany(): void
    {
        if (!function_exists('company') || !company()) {
            return;
        }

        self::addModuleSetting(company());
    }
}
