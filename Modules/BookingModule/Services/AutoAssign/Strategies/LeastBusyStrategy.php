<?php

namespace Modules\BookingModule\Services\AutoAssign\Strategies;

use Carbon\Carbon;
use Modules\BookingModule\Entities\Appointment;

class LeastBusyStrategy extends BaseStrategy
{
    public function pickAssigneeUserId(Appointment $appointment): ?int
    {
        $candidates = $this->eligibleUserIds($appointment);
        if (!$candidates) {
            return null;
        }

        $startDate = Carbon::now()->subDays(1);
        $endDate = Carbon::now()->addDays(30);

        $counts = Appointment::query()
            ->selectRaw('assigned_to, COUNT(*) as c')
            ->whereIn('assigned_to', $candidates)
            ->whereNotNull('assigned_to')
            ->whereBetween('appointment_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->groupBy('assigned_to')
            ->pluck('c', 'assigned_to')
            ->all();

        $bestUserId = null;
        $bestCount = PHP_INT_MAX;
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
