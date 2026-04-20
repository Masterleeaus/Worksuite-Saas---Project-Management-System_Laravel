<?php

namespace Modules\BookingModule\Console;

use Illuminate\Console\Command;
use Modules\BookingModule\Jobs\SendUpcomingBookingNotificationJob;
use Modules\BookingModule\Services\BookingReminderService;

/**
 * DispatchBookingRemindersCommand
 *
 * Dispatches SendUpcomingBookingNotificationJob for each configured lead time.
 * Should be scheduled to run every minute via app/Console/Kernel.php.
 *
 * Usage:
 *   php artisan bookingmodule:send-reminders
 */
class DispatchBookingRemindersCommand extends Command
{
    protected $signature = 'bookingmodule:send-reminders';

    protected $description = 'Dispatch upcoming booking reminder notifications';

    public function handle(BookingReminderService $reminderService): int
    {
        $leadTimes = $reminderService->reminderLeadTimes();

        foreach ($leadTimes as $leadMinutes) {
            $leadMinutes = (int) $leadMinutes;
            if ($leadMinutes > 0) {
                SendUpcomingBookingNotificationJob::dispatch($leadMinutes)
                    ->onQueue('notifications');
            }
        }

        $this->line('Booking reminder jobs dispatched for lead times: ' . implode(', ', $leadTimes) . ' minutes.');

        return 0;
    }
}
