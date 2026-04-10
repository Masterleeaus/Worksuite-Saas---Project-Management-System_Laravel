<?php

namespace Modules\BookingModule\Services\AutoAssign;

use Modules\BookingModule\Entities\Schedule;
use Modules\BookingModule\Services\AppointmentSettingsService;
use Modules\BookingModule\Services\ScheduleAssignmentService;
use Modules\BookingModule\Services\AutoAssign\EligibleUsersResolver;

class ScheduleAutoAssignEngine
{
    public function __construct(
        protected AppointmentSettingsService $settings,
        protected ScheduleAssignmentService $assignmentService,
    ) {}

    public function maybeAutoAssign(Schedule $schedule): Schedule
    {
        $effective = $schedule->effective_assignee_id;
        if ($effective) {
            return $schedule;
        }

        $enabled = (bool)($this->settings->get('auto_assign.enabled', config('bookingmodule::auto_assign.enabled', false)));
        if (!$enabled) {
            return $schedule;
        }

        // For bookings, we use least_busy by default (counts bookings on the requested date).
        $strategyKey = (string)($this->settings->get('auto_assign.strategy', config('bookingmodule::auto_assign.strategy', 'least_busy')));
        $resolver = new EligibleUsersResolver();
        $candidates = $resolver->eligibleUserIds($schedule->workspace, $schedule->created_by);
        if (!$candidates) {
            return $schedule;
        }

        $pick = $this->pick($strategyKey, $schedule, $candidates);
        if (!$pick && config('bookingmodule::auto_assign.fallback_unassigned', true)) {
            return $schedule;
        }
        return $this->assignmentService->assign($schedule, $pick, 'Auto-assigned booking');
    }

    protected function pick(string $strategyKey, Schedule $schedule, array $candidates): ?int
    {
        return match ($strategyKey) {
            'round_robin' => $this->roundRobin($candidates),
            default => $this->leastBusyOnDate($schedule, $candidates),
        };
    }

    protected function roundRobin(array $candidates): ?int
    {
        $key = 'auto_assign.round_robin.pointer';
        $pointer = (int)($this->settings->get($key, 0));
        $index = $pointer % count($candidates);
        $pick = $candidates[$index] ?? null;
        $this->settings->set($key, $pointer + 1);
        return $pick ? (int)$pick : null;
    }

    protected function leastBusyOnDate(Schedule $schedule, array $candidates): ?int
    {
        $date = (string)$schedule->date;
        $counts = Schedule::query()
            ->selectRaw('COALESCE(assigned_to, user_id) as staff_id, COUNT(*) as c')
            ->whereIn('user_id', $candidates)
            ->where('date', $date)
            ->groupBy('staff_id')
            ->pluck('c', 'staff_id')
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
