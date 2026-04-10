<?php

namespace Modules\Sms\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Event;
use Modules\Sms\Events\CleaningUpcomingReminderEvent;

/**
 * Dispatches UpcomingReminder notifications for cleaning jobs scheduled
 * within the next 24–25 hours (run this command every hour via cron).
 *
 * Usage: php artisan sms:cleaning:upcoming-reminders
 */
class UpcomingCleaningReminderCommand extends Command
{
    protected $signature = 'sms:cleaning:upcoming-reminders';

    protected $description = 'Send 24-hour advance reminders for upcoming cleaning jobs via SMS/WhatsApp';

    public function handle(): int
    {
        if (!class_exists(\Modules\FSMCore\Models\FSMOrder::class)) {
            $this->warn('FSMCore module not installed – skipping.');
            return self::SUCCESS;
        }

        $windowStart = Carbon::now()->addHours(24);
        $windowEnd   = Carbon::now()->addHours(25);

        $orders = \Modules\FSMCore\Models\FSMOrder::query()
            ->whereBetween('scheduled_date_start', [$windowStart, $windowEnd])
            ->with(['location', 'person', 'stage'])
            ->get();

        $this->info("Found {$orders->count()} order(s) in the 24-hour window.");

        foreach ($orders as $order) {
            Event::dispatch(new CleaningUpcomingReminderEvent($order));
        }

        return self::SUCCESS;
    }
}
