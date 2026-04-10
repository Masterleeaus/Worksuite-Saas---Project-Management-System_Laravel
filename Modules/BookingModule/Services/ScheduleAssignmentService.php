<?php

namespace Modules\BookingModule\Services;

use Illuminate\Support\Facades\Auth;
use Modules\BookingModule\Entities\Schedule;
use Modules\BookingModule\Entities\ScheduleAssignment;
use Modules\BookingModule\Notifications\ScheduleAssignedNotification;
use Modules\BookingModule\Notifications\ScheduleReassignedNotification;
use Modules\BookingModule\Services\NotificationGate;

class ScheduleAssignmentService
{
    /**
     * Assign / reassign / unassign a schedule to a staff user.
     * This module uses schedules.assigned_to as the canonical assignee field.
     */
    public function assign(Schedule $schedule, ?int $toUserId, ?string $note = null): Schedule
    {
        $fromUserId = $schedule->effective_assignee_id;

        $schedule->assigned_to = $toUserId;
        $schedule->assigned_by = Auth::id();
        $schedule->assigned_at = now();
        $schedule->assignment_status = $toUserId ? 'assigned' : 'unassigned';
        // NOTE: We no longer sync legacy schedules.user_id. That column may exist for older installs but is deprecated.
        $schedule->save();

        ScheduleAssignment::create([
            'schedule_id'   => $schedule->id,
            'from_user_id'  => $fromUserId,
            'to_user_id'    => $toUserId,
            'action'        => ($fromUserId && $toUserId && $fromUserId !== $toUserId)
                ? 'reassign'
                : ($toUserId ? 'assign' : 'unassign'),
            'note'          => $note,
            'created_by'    => function_exists('creatorId') ? creatorId() : Auth::id(),
            'workspace'     => function_exists('getActiveWorkSpace') ? getActiveWorkSpace() : null,
        ]);

        try {
            if ($toUserId && $schedule->assignee) {
                if ($fromUserId && $fromUserId !== $toUserId) {
                    if ($gate->canNotify($schedule->assignee->id ?? null, $companyId, 'reassigned')) {
                    $schedule->assignee->notify(new ScheduleReassignedNotification($schedule));
                }
                } else {
                    if ($gate->canNotify($schedule->assignee->id ?? null, $companyId, 'assigned')) {
                    $schedule->assignee->notify(new ScheduleAssignedNotification($schedule));
                }
                }
            }
        } catch (\Throwable $e) {
            // Ignore notification failures.
        }

        return $schedule;
    }
}
