<?php

namespace Modules\BookingModule\Services\Dispatch;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\BookingModule\Entities\Schedule;
use Modules\BookingModule\Entities\ScheduleAssignment;
use Modules\BookingModule\Services\ScheduleCapacityService;
use Modules\BookingModule\Services\ScheduleConflictService;

class DispatchScheduleUpdateService
{
    public function __construct(
        protected ScheduleCapacityService $capacity,
        protected ScheduleConflictService $conflicts,
    ) {}

    /**
     * Update schedule date/time and (optionally) assigned user.
     * Enforces capacity and conflict rules when assignment changes or times change.
     */
    public function update(int $scheduleId, array $payload): array
    {
        $schedule = Schedule::find($scheduleId);
        if (!$schedule) {
            return ['ok' => false, 'message' => 'Schedule not found'];
        }

        $toUserId = isset($payload['user_id']) && $payload['user_id'] ? (int)$payload['user_id'] : null;

        $schedule->date = $payload['date'];
        $schedule->start_time = $payload['start_time'];
        $schedule->end_time = $payload['end_time'];

        if (array_key_exists('notes', $payload)) {
            $schedule->notes = $payload['notes'];
        }

        // Apply assignment (optional)
        if ($toUserId === null) {
            $schedule->assigned_to = null;
            $schedule->user_id = null;
            $schedule->assignment_status = 'unassigned';
            $schedule->assigned_at = null;
            $schedule->assigned_by = null;
        } else {
            // Capacity check
            [$ok, $msg] = $this->capacity->canAssignUserToSchedule($schedule, $toUserId);
            if (!$ok) {
                return ['ok' => false, 'message' => $msg ?? 'Capacity limit'];
            }

            $effective = $this->capacity->getEffectiveCapacity((int)$schedule->created_by, (int)$schedule->workspace, $toUserId);
            if (!empty($effective['enforce_conflicts'])) {
                if ($this->conflicts->hasConflict($schedule, $toUserId, (bool)($effective['count_pending_too'] ?? false))) {
                    return ['ok' => false, 'message' => __('bookingmodule::capacity.errors.conflict')];
                }
            }

            $schedule->assigned_to = $toUserId;
            $schedule->user_id = $toUserId; // compatibility
            $schedule->assignment_status = 'assigned';
            $schedule->assigned_at = Carbon::now();
            $schedule->assigned_by = Auth::id();
        }

        $schedule->save();

        // Audit history
        $history = new ScheduleAssignment();
        $history->schedule_id = $schedule->id;
        $history->assigned_to = $schedule->assigned_to;
        $history->assigned_by = Auth::id();
        $history->action = 'dispatch_update';
        $history->notes = 'Dispatch quick edit update';
        $history->save();

        return ['ok' => true, 'message' => 'Updated', 'schedule_id' => $schedule->id];
    }
}
