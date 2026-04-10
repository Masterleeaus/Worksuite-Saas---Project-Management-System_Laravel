<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Modules\BookingModule\Entities\BookingModuleSetting;

App::booted(function () {
    try {
        if (app()->runningInConsole() || !function_exists('user') || !user()) {
            return;
        }

        $companyId = user()->company_id ?? null;
        if (!$companyId) {
            return;
        }

        $cacheKey = 'bookingmodule_sidebar_self_heal_' . $companyId;
        if (Cache::has($cacheKey)) {
            return;
        }

        BookingModuleSetting::ensureActiveForCurrentCompany();
        Cache::put($cacheKey, true, now()->addMinutes(10));
        Cache::forget('user_modules_' . user()->id);
        Cache::forget('worksuite_plugins');
    } catch (\Throwable $e) {
        // Silent compatibility fallback.
    }
});
