<?php

namespace Modules\BookingModule\Services\Dispatch;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\BookingModule\Entities\Schedule;
use Modules\BookingModule\Entities\ScheduleAssignment;
use Modules\BookingModule\Services\ScheduleCapacityService;
use Modules\BookingModule\Services\ScheduleConflictService;

class DispatchMoveService
{
    public function __construct(
        protected ScheduleCapacityService $capacity,
        protected ScheduleConflictService $conflicts,
    ) {}

    public function move(int $scheduleId, ?int $toUserId, string $date, string $startTime, string $endTime, string $note = ''): array
    {
        $schedule = Schedule::find($scheduleId);
        if (!$schedule) {
            return ['ok' => false, 'message' => 'Schedule not found'];
        }

        $fromUserId = $schedule->assigned_to ?? $schedule->user_id;

        // Update timing
        $schedule->date = Carbon::parse($date)->toDateString();
        $schedule->start_time = $startTime;
        $schedule->end_time = $endTime;

        // Update assignment
        if ($toUserId === null) {
            $schedule->assigned_to = null;
            $schedule->user_id = null;
            $schedule->assignment_status = 'unassigned';
            $schedule->assigned_at = null;
            $schedule->assigned_by = null;
        } else {
            // Capacity + conflict checks
            [$ok, $msg] = $this->capacity->canAssignUserToSchedule($schedule, $toUserId);
            if (!$ok) {
                return ['ok' => false, 'message' => $msg ?? 'Capacity limit'];
            }

            $effective = $this->capacity->getEffectiveCapacity((int)$schedule->created_by, (int)$schedule->workspace, $toUserId);
            if (!empty($effective['enforce_conflicts'])) {
                if ($this->conflicts->hasConflict($schedule, $toUserId, (bool)$effective['count_pending_too'])) {
                    return ['ok' => false, 'message' => __('bookingmodule::capacity.errors.conflict')];
                }
            }

            $schedule->assigned_to = $toUserId;
            $schedule->user_id = $toUserId; // backwards compat
            $schedule->assigned_at = now();
            $schedule->assigned_by = Auth::id();
            $schedule->assignment_status = 'assigned';
        }

        $schedule->save();

        // Log history (always)
        ScheduleAssignment::create([
            'schedule_id' => $schedule->id,
            'from_user_id' => $fromUserId,
            'to_user_id' => $toUserId,
            'action' => $toUserId === null ? 'unassign' : ($fromUserId ? 'reassign' : 'assign'),
            'note' => $note,
            'created_by' => $schedule->created_by,
            'workspace' => $schedule->workspace,
        ]);

        return [
            'ok' => true,
            'message' => 'Moved',
            'schedule' => [
                'id' => $schedule->id,
                'date' => $schedule->date,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'assigned_to' => $schedule->assigned_to,
            ]
        ];
    }
}
