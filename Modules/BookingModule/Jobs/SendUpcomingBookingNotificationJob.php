<?php

namespace Modules\BookingModule\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\BookingModule\Services\BookingReminderService;

/**
 * SendUpcomingBookingNotificationJob
 *
 * Scans schedules that are due for a reminder at a given lead time (scoped to a
 * specific company) and fans out individual SendBookingReminderJob dispatches
 * per schedule.
 *
 * Dispatched by DispatchBookingRemindersCommand every minute, once per active
 * company per configured lead time:
 *   SendUpcomingBookingNotificationJob::dispatch($companyId, $leadMinutes)
 */
class SendUpcomingBookingNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;
    public int $timeout = 120;

    public function __construct(
        public readonly int $companyId,
        public readonly int $leadMinutes = 1440,
    ) {}

    public function handle(BookingReminderService $reminderService): void
    {
        $schedules = $reminderService->dueSchedules($this->leadMinutes, $this->companyId);

        foreach ($schedules as $schedule) {
            SendBookingReminderJob::dispatch(
                $schedule->id,
                $this->companyId,
                $this->leadMinutes,
                SendBookingReminderJob::TRIGGER_SCHEDULED,
            )->onQueue('notifications');
        }
    }

    /**
     * Unique job ID prevents duplicate queuing for the same company + lead time scan.
     */
    public function uniqueId(): string
    {
        return 'upcoming-reminders-' . $this->companyId . '-' . $this->leadMinutes;
    }
}
