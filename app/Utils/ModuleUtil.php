<?php

namespace App\Utils;

use App\Models\ModuleSetting;
use Illuminate\Support\Facades\Schema;

class ModuleUtil
{
    public function is_admin($user): bool
    {
        if (!$user) {
            return false;
        }

        return (bool) ($user->hasRole('admin') || $user->hasRole('superadmin') || ($user->is_superadmin ?? false));
    }

    public function hasThePermissionInSubscription($businessId, $moduleName): bool
    {
        if (!$businessId || !$moduleName) {
            return false;
        }

        $query = ModuleSetting::withoutGlobalScopes()
            ->where('company_id', $businessId)
            ->where('module_name', $moduleName)
            ->where('status', 'active');

        if (Schema::hasColumn('module_settings', 'is_allowed')) {
            $query->where('is_allowed', 1);
        }

        return $query->exists();
    }
}
