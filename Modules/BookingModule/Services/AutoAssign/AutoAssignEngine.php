<?php

namespace Modules\BookingModule\Services\AutoAssign;

use Illuminate\Support\Facades\Config;
use Modules\BookingModule\Entities\Appointment;
use Modules\BookingModule\Services\AppointmentAssignmentService;
use Modules\BookingModule\Services\AppointmentSettingsService;
use Modules\BookingModule\Services\AutoAssign\Strategies\LeastBusyStrategy;
use Modules\BookingModule\Services\AutoAssign\Strategies\RoundRobinStrategy;
use Modules\BookingModule\Services\AutoAssign\Strategies\ScheduleMatchStrategy;

class AutoAssignEngine
{
    public function __construct(
        protected AppointmentSettingsService $settings,
        protected AppointmentAssignmentService $assignmentService,
    ) {}

    public function maybeAutoAssign(Appointment $appointment): Appointment
    {
        if ($appointment->assigned_to) {
            return $appointment;
        }

        $enabled = (bool)($this->settings->get('auto_assign.enabled', config('bookingmodule::auto_assign.enabled', false)));
        if (!$enabled) {
            return $appointment;
        }

        $strategyKey = (string)($this->settings->get('auto_assign.strategy', config('bookingmodule::auto_assign.strategy', 'schedule_match')));

        $strategy = match ($strategyKey) {
            'round_robin' => new RoundRobinStrategy($this->settings),
            'least_busy' => new LeastBusyStrategy($this->settings),
            default => new ScheduleMatchStrategy($this->settings),
        };

        $candidate = $strategy->pickAssigneeUserId($appointment);

        if (!$candidate && config('bookingmodule::auto_assign.fallback_unassigned', true)) {
            return $appointment;
        }

        return $this->assignmentService->assign($appointment, $candidate, 'Auto-assigned');
    }
}
