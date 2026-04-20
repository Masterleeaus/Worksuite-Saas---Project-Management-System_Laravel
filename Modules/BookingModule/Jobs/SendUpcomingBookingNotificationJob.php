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
 * Scans schedules that are due for a reminder at a given lead time and fans
 * out individual SendBookingReminderJob dispatches per schedule.
 *
 * Intended to be dispatched by a scheduled console command every minute:
 *   $schedule->job(new SendUpcomingBookingNotificationJob(1440))->everyMinute();
 *   $schedule->job(new SendUpcomingBookingNotificationJob(120))->everyMinute();
 */
class SendUpcomingBookingNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;
    public int $timeout = 120;

    public function __construct(
        public readonly int $leadMinutes = 1440,
    ) {}

    public function handle(BookingReminderService $reminderService): void
    {
        $schedules = $reminderService->dueSchedules($this->leadMinutes);

        foreach ($schedules as $schedule) {
            SendBookingReminderJob::dispatch(
                $schedule->id,
                $schedule->company_id ?? 0,
                $this->leadMinutes,
            )->onQueue('notifications');
        }
    }
}
