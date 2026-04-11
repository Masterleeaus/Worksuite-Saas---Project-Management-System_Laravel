<?php

namespace Modules\SynapseDispatch\Services;

use Carbon\Carbon;
use Modules\SynapseDispatch\Models\DispatchJob;
use Modules\SynapseDispatch\Models\DispatchWorker;
use Modules\SynapseDispatch\Enums\PlanningStatus;

class WorkerAvailabilityService
{
    /**
     * Return true if the given worker has no overlapping dispatched job
     * during [start, start + durationMinutes).
     */
    public function isAvailable(DispatchWorker $worker, Carbon $start, float $durationMinutes): bool
    {
        $end = $start->copy()->addMinutes($durationMinutes);

        return !DispatchJob::where('scheduled_primary_worker_id', $worker->id)
            ->whereIn('planning_status', [PlanningStatus::DISPATCHED->value, PlanningStatus::PLANNED->value])
            ->where('scheduled_start_datetime', '<', $end)
            ->whereRaw(
                'DATE_ADD(scheduled_start_datetime, INTERVAL scheduled_duration_minutes MINUTE) > ?',
                [$start]
            )
            ->exists();
    }
}
