<?php

namespace Modules\BookingModule\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\BookingModule\Entities\Schedule;
use Modules\BookingModule\Notifications\ScheduleReminder24hNotification;
use Modules\BookingModule\Notifications\ScheduleReminder2hNotification;

/**
 * SendBookingReminderJob
 *
 * Sends a time-sensitive reminder notification to the assignee of a schedule.
 * Called from BookingCompletedListener and from scheduled console commands.
 *
 * The lead_minutes parameter controls which notification class is used:
 *   1440 → 24-hour reminder
 *   120  → 2-hour reminder
 *   other → 24-hour notification as fallback
 */
class SendBookingReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 30;

    public function __construct(
        public readonly int $scheduleId,
        public readonly int $companyId,
        public readonly int $leadMinutes = 1440,
    ) {}

    public function handle(): void
    {
        $schedule = Schedule::find($this->scheduleId);

        if (!$schedule || !$schedule->assigned_to || !$schedule->assignee) {
            return;
        }

        // Build context array passed into the notification for richer email content.
        $data = [
            'date'       => $schedule->date ?? ($schedule->starts_at ? $schedule->starts_at->toDateString() : null),
            'start_time' => $schedule->start_time ?? ($schedule->starts_at ? $schedule->starts_at->format('H:i') : null),
            'location'   => $schedule->location ?? null,
            'schedule_id' => $this->scheduleId,
            'company_id'  => $this->companyId,
        ];

        $notification = match (true) {
            $this->leadMinutes <= 120 => new ScheduleReminder2hNotification($data),
            default                   => new ScheduleReminder24hNotification($data),
        };

        try {
            $schedule->assignee->notify($notification);
        } catch (\Throwable $e) {
            // Swallow notification failures — do not fail the job on mail errors.
        }
    }

    public function uniqueId(): string
    {
        return 'reminder-' . $this->scheduleId . '-' . $this->leadMinutes;
    }
}
