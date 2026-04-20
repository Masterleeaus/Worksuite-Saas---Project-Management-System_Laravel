<?php

namespace Modules\BookingModule\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\BookingModule\Entities\Schedule;
use Modules\BookingModule\Services\ScheduleCapacityService;

/**
 * RecalculateScheduleCapacityJob
 *
 * Triggered by AppointmentStatus events.  Re-evaluates capacity state for the
 * affected schedule so that the dispatch board reflects accurate availability.
 *
 * This job only reads + caches capacity data; it does NOT mutate schedule
 * assignment records.  Business logic remains in ScheduleCapacityService.
 */
class RecalculateScheduleCapacityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 30;

    public function __construct(
        public readonly int $scheduleId,
        public readonly int $companyId,
    ) {}

    public function handle(ScheduleCapacityService $capacityService): void
    {
        $schedule = Schedule::find($this->scheduleId);

        if (!$schedule) {
            return;
        }

        // Re-check capacity for the assignee (if any) to surface warnings early.
        $assigneeId = $schedule->assigned_to ?? $schedule->user_id;

        if (!$assigneeId) {
            return;
        }

        // Delegate to service — result can be consumed by API/UI cache invalidation.
        $capacityService->getEffectiveCapacity(
            (int) $schedule->created_by,
            (int) $schedule->workspace,
            (int) $assigneeId,
        );
    }

    public function uniqueId(): string
    {
        return 'recalc-capacity-' . $this->scheduleId;
    }
}
