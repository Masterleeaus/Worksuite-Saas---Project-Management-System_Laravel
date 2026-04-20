<?php

namespace Modules\BookingModule\Console;

use App\Models\Company;
use App\Models\ModuleSetting;
use App\Scopes\CompanyScope;
use Illuminate\Console\Command;
use Modules\BookingModule\Jobs\SendUpcomingBookingNotificationJob;
use Modules\BookingModule\Services\BookingReminderService;

/**
 * DispatchBookingRemindersCommand
 *
 * Iterates every company that has the BookingModule active and dispatches
 * SendUpcomingBookingNotificationJob per company × lead-time combination.
 * Lead times are resolved per company from AppointmentSettings (DB) with
 * config / hard-coded fallback.
 *
 * Scheduled to run every minute via app/Console/Kernel.php.
 * Uses withoutOverlapping() to prevent double-dispatch under slow queues.
 *
 * Usage:
 *   php artisan bookingmodule:send-reminders
 */
class DispatchBookingRemindersCommand extends Command
{
    protected $signature = 'bookingmodule:send-reminders';

    protected $description = 'Dispatch upcoming booking reminder notifications (multi-tenant)';

    public function handle(BookingReminderService $reminderService): int
    {
        // Collect company IDs that have bookingmodule active.
        $activeCompanyIds = ModuleSetting::withoutGlobalScope(CompanyScope::class)
            ->where('module_name', 'bookingmodule')
            ->where('status', 'active')
            ->where('is_allowed', 1)
            ->pluck('company_id')
            ->filter()
            ->unique()
            ->values();

        if ($activeCompanyIds->isEmpty()) {
            $this->line('BookingModule: no active companies found, nothing to dispatch.');
            return 0;
        }

        $dispatched = 0;

        foreach ($activeCompanyIds as $companyId) {
            $companyId = (int) $companyId;
            $leadTimes = $reminderService->reminderLeadTimesForCompany($companyId);

            foreach ($leadTimes as $leadMinutes) {
                $leadMinutes = (int) $leadMinutes;
                if ($leadMinutes <= 0) {
                    continue;
                }

                SendUpcomingBookingNotificationJob::dispatch($companyId, $leadMinutes)
                    ->onQueue('notifications');
                $dispatched++;
            }
        }

        $this->line(sprintf(
            'BookingModule: dispatched %d reminder scan job(s) for %d company/companies.',
            $dispatched,
            $activeCompanyIds->count()
        ));

        return 0;
    }
}
