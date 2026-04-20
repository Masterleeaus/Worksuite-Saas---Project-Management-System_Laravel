<?php

namespace Modules\BookingModule\Services;

use Illuminate\Support\Facades\Auth;
use Modules\BookingModule\Entities\Schedule;
use Modules\BookingModule\Entities\ScheduleAssignment;
use Modules\BookingModule\Jobs\SendBookingReminderJob;
use Modules\BookingModule\Notifications\ScheduleAssignedNotification;
use Modules\BookingModule\Notifications\ScheduleReassignedNotification;
use Modules\BookingModule\Services\NotificationGate;

class ScheduleAssignmentService
{
    public function __construct(
        protected NotificationGate       $gate,
        protected BookingReminderService $reminderService,
    ) {}

    /**
     * Assign / reassign / unassign a schedule to a staff user.
     * This module uses schedules.assigned_to as the canonical assignee field.
     */
    public function assign(Schedule $schedule, ?int $toUserId, ?string $note = null): Schedule
    {
        $fromUserId = $schedule->effective_assignee_id ?? $schedule->assigned_to;
        $companyId  = (int) ($schedule->company_id ?? 0);
        $isReassign = $fromUserId && $toUserId && $fromUserId !== $toUserId;

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
            'action'        => $isReassign ? 'reassign' : ($toUserId ? 'assign' : 'unassign'),
            'note'          => $note,
            'created_by'    => function_exists('creatorId') ? creatorId() : Auth::id(),
            'workspace'     => function_exists('getActiveWorkSpace') ? getActiveWorkSpace() : null,
        ]);

        try {
            if ($toUserId && $schedule->assignee) {
                if ($isReassign) {
                    if ($this->gate->canNotify($schedule->assignee->id ?? null, $companyId, 'reassigned')) {
                        $schedule->assignee->notify(new ScheduleReassignedNotification(['schedule_id' => $schedule->id]));
                    }
                } else {
                    if ($this->gate->canNotify($schedule->assignee->id ?? null, $companyId, 'assigned')) {
                        $schedule->assignee->notify(new ScheduleAssignedNotification(['schedule_id' => $schedule->id]));
                    }
                }
            }
        } catch (\Throwable $e) {
            // Ignore notification failures.
        }

        // Dispatch reminder jobs when enabled by automation settings.
        if ($toUserId && $companyId) {
            if ($isReassign) {
                $this->maybeDispatchReminders($schedule, $companyId, 'reschedule');
            } else {
                $this->maybeDispatchReminders($schedule, $companyId, 'assign');
            }
        }

        return $schedule;
    }

    /**
     * Dispatch SendBookingReminderJob for each configured lead time if
     * the corresponding automation setting is enabled for this company.
     *
     * @param  'assign'|'reschedule'  $trigger
     */
    private function maybeDispatchReminders(Schedule $schedule, int $companyId, string $trigger): void
    {
        $enabled = $trigger === 'reschedule'
            ? $this->reminderService->remindersOnRescheduleForCompany($companyId)
            : $this->reminderService->remindersOnAssignForCompany($companyId);

        if (!$enabled) {
            return;
        }

        $leadTimes = $this->reminderService->reminderLeadTimesForCompany($companyId);

        foreach ($leadTimes as $leadMinutes) {
            $leadMinutes = (int) $leadMinutes;
            if ($leadMinutes <= 0) {
                continue;
            }

            SendBookingReminderJob::dispatch($schedule->id, $companyId, $leadMinutes)
                ->onQueue('notifications');
        }
    }
}
