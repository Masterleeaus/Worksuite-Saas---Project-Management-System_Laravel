<?php

namespace Modules\BookingModule\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * PruneBookingReminderLogsCommand
 *
 * Deletes old rows from booking_reminder_logs to prevent unbounded table growth.
 * A row is eligible for pruning when the schedule's date+time is older than the
 * configured retention window (default 90 days).
 *
 * Because booking_reminder_logs does not store the schedule date directly we prune
 * by sent_at — once a reminder log is older than the retention window the
 * corresponding schedule has certainly already passed, so the log row is no longer
 * useful for dedup purposes.
 *
 * Configuration:
 *   config('bookingmodule.automation.reminders.retention_days')  default 90
 *   env: BOOKING_REMINDER_RETENTION_DAYS
 *
 * Usage:
 *   php artisan bookingmodule:prune-reminder-logs
 *   php artisan bookingmodule:prune-reminder-logs --days=30
 */
class PruneBookingReminderLogsCommand extends Command
{
    protected $signature = 'bookingmodule:prune-reminder-logs
        {--days= : Override retention window in days (overrides config)}';

    protected $description = 'Prune old rows from booking_reminder_logs (maintenance)';

    public function handle(): int
    {
        $days = $this->option('days') !== null
            ? (int) $this->option('days')
            : (int) config('bookingmodule.automation.reminders.retention_days', 90);

        if ($days <= 0) {
            $this->error('Retention days must be a positive integer.');
            return 1;
        }

        $cutoff = now()->subDays($days);

        // booking_reminder_logs has no updated_at; prune by sent_at.
        $deleted = DB::table('booking_reminder_logs')
            ->where('sent_at', '<', $cutoff->toDateTimeString())
            ->delete();

        $this->line(sprintf(
            'BookingModule: pruned %d reminder log row(s) older than %d day(s) (cutoff: %s).',
            $deleted,
            $days,
            $cutoff->toDateTimeString()
        ));

        return 0;
    }
}
