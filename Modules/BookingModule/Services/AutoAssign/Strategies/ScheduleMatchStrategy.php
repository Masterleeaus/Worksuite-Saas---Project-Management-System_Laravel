<?php

namespace Modules\BookingModule\Services\AutoAssign\Strategies;

use Modules\BookingModule\Entities\Appointment;
use Modules\BookingModule\Entities\Schedule;

/**
 * "Schedule match" for this module means "avoid double-booking".
 * We pick the eligible user with the fewest overlapping bookings for the requested slot.
 */
class ScheduleMatchStrategy extends BaseStrategy
{
    public function pickAssigneeUserId(Appointment $appointment): ?int
    {
        $candidates = $this->eligibleUserIds($appointment);
        if (!$candidates) {
            return null;
        }

        // Appointments in this module are the "appointment types".
        // Bookings are stored in the schedules table, so we can't match without slot context.
        // We fallback to the least-busy candidate for the next 30 days.
        $bestUserId = null;
        $bestCount = PHP_INT_MAX;

        $startDate = now()->subDay()->toDateString();
        $endDate = now()->addDays(30)->toDateString();

        $counts = Schedule::query()
            ->selectRaw('COALESCE(assigned_to, user_id) as staff_id, COUNT(*) as c')
            ->whereIn('user_id', $candidates)
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('staff_id')
            ->pluck('c', 'staff_id')
            ->all();

        foreach ($candidates as $userId) {
            $c = (int)($counts[$userId] ?? 0);
            if ($c < $bestCount) {
                $bestCount = $c;
                $bestUserId = (int)$userId;
            }
        }

        return $bestUserId;
    }
}
