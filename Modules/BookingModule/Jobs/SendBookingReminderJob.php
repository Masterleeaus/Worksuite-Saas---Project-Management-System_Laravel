<?php

namespace Modules\BookingModule\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Modules\BookingModule\Entities\Schedule;
use Modules\BookingModule\Notifications\ScheduleReminder24hNotification;
use Modules\BookingModule\Notifications\ScheduleReminder2hNotification;

/**
 * SendBookingReminderJob
 *
 * Sends a time-sensitive reminder notification to the assignee of a schedule.
 * Dispatched by:
 *   - SendUpcomingBookingNotificationJob (scheduled scan, every minute)
 *   - ScheduleAssignmentService (on assign / reschedule when automation setting enabled)
 *   - DispatchMoveService (dispatch-board drag/drop rescheduling)
 *   - DispatchScheduleUpdateService (dispatch-board quick edit)
 *
 * The lead_minutes parameter controls which notification class is used:
 *   1440 → 24-hour reminder
 *   120  → 2-hour reminder
 *   other → 24-hour notification as fallback
 *
 * DB-level dedupe: booking_reminder_logs enforces unique(schedule_id, lead_minutes, trigger)
 * so retried or duplicated jobs never re-send the same reminder.
 */
class SendBookingReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 30;

    /** Trigger type stored in booking_reminder_logs for dedupe. */
    public const TRIGGER_SCHEDULED  = 'scheduled';
    public const TRIGGER_ASSIGN     = 'assign';
    public const TRIGGER_RESCHEDULE = 'reschedule';

    public function __construct(
        public readonly int    $scheduleId,
        public readonly int    $companyId,
        public readonly int    $leadMinutes = 1440,
        public readonly string $trigger     = self::TRIGGER_SCHEDULED,
    ) {}

    public function handle(): void
    {
        // DB-level idempotency: insertOrIgnore on the unique key.
        // Returns 0 rows affected when a duplicate exists → bail out safely.
        $inserted = DB::table('booking_reminder_logs')->insertOrIgnore([
            'schedule_id'  => $this->scheduleId,
            'company_id'   => $this->companyId,
            'lead_minutes' => $this->leadMinutes,
            'trigger'      => $this->trigger,
            'sent_at'      => now(),
        ]);

        if ($inserted === 0) {
            // Already sent — safe to discard this attempt.
            return;
        }

        $schedule = Schedule::find($this->scheduleId);

        if (!$schedule || !$schedule->assigned_to || !$schedule->assignee) {
            // Schedule gone or unassigned — remove the log row so it can re-fire if re-assigned.
            DB::table('booking_reminder_logs')
                ->where('schedule_id', $this->scheduleId)
                ->where('lead_minutes', $this->leadMinutes)
                ->where('trigger', $this->trigger)
                ->delete();
            return;
        }

        // Build context array passed into the notification for richer email content.
        $data = [
            'date'        => $schedule->date ?? ($schedule->starts_at ? $schedule->starts_at->toDateString() : null),
            'start_time'  => $schedule->start_time ?? ($schedule->starts_at ? $schedule->starts_at->format('H:i') : null),
            'location'    => $schedule->location ?? null,
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
            // Remove the log row so the reminder can be re-attempted on next run.
            DB::table('booking_reminder_logs')
                ->where('schedule_id', $this->scheduleId)
                ->where('lead_minutes', $this->leadMinutes)
                ->where('trigger', $this->trigger)
                ->delete();
        }
    }

    public function uniqueId(): string
    {
        return 'reminder-' . $this->scheduleId . '-' . $this->leadMinutes . '-' . $this->trigger;
    }
}
