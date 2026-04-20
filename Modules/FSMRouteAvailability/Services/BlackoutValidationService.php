<?php

namespace Modules\FSMRouteAvailability\Services;

use Carbon\Carbon;
use Modules\FSMRouteAvailability\Models\FSMBlackoutDay;

class BlackoutValidationService
{
    /**
     * Check if the given date is blocked for the given route.
     * Optionally filter by zip code.
     * Returns the blocking BlackoutDay or null.
     */
    public function findBlockingDay(int $routeId, Carbon $date, ?string $zip = null): ?FSMBlackoutDay
    {
        // Load all blackout groups for this route
        $groups = \Modules\FSMRoute\Models\FSMRoute::find($routeId)?->blackoutGroups ?? collect();

        foreach ($groups as $group) {
            foreach ($group->blackoutDays as $day) {
                if ($day->blocks($date, $zip)) {
                    return $day;
                }
            }
        }

        return null;
    }
}
