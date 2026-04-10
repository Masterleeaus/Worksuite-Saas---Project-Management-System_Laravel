<?php

namespace Modules\BookingModule\Services\AutoAssign\Strategies;

use Modules\BookingModule\Entities\Appointment;
use Modules\BookingModule\Services\AppointmentSettingsService;
use Modules\BookingModule\Services\AutoAssign\EligibleUsersResolver;

abstract class BaseStrategy implements AutoAssignStrategy
{
    public function __construct(protected AppointmentSettingsService $settings)
    {
    }

    protected function eligibleUserIds(Appointment $appointment): array
    {
        $resolver = new EligibleUsersResolver();
        $workspace = $appointment->workspace ?? (function_exists('getActiveWorkSpace') ? getActiveWorkSpace() : null);
        $createdBy = $appointment->created_by ?? (function_exists('creatorId') ? creatorId() : null);

        return $resolver->eligibleUserIds($workspace, $createdBy);
    }
}
