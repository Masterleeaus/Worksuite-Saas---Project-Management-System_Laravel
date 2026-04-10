<?php

namespace Modules\CustomerConnect\Services\Safety;

use Carbon\Carbon;

class QuietHoursService
{
    public function isQuietNow(?string $tz = null): bool
    {
        $cfg = config('customerconnect.quiet_hours', []);
        if (!($cfg['enabled'] ?? false)) {
            return false;
        }

        $now = $tz ? Carbon::now($tz) : Carbon::now();
        $day = strtolower($now->format('D')); // mon,tue,...
        $allowedDays = $cfg['days'] ?? [];
        if ($allowedDays && !in_array(substr($day, 0, 3), $allowedDays, true)) {
            return false;
        }

        $start = $cfg['start'] ?? '20:00';
        $end   = $cfg['end']   ?? '08:00';

        $startAt = Carbon::parse($start, $now->timezone)->setDate($now->year, $now->month, $now->day);
        $endAt   = Carbon::parse($end,   $now->timezone)->setDate($now->year, $now->month, $now->day);

        // Handle overnight window (e.g., 20:00 -> 08:00)
        if ($endAt->lessThanOrEqualTo($startAt)) {
            $endAt->addDay();
            if ($now->lessThan($startAt)) {
                $startAt->subDay();
            }
        }

        return $now->betweenIncluded($startAt, $endAt);
    }
}
