<?php

namespace Modules\BookingModule\Services;

use Carbon\Carbon;
use Modules\BookingModule\Entities\Schedule;

/**
 * BookingReminderService
 *
 * Determines whether a reminder notification should be dispatched for a given
 * schedule based on configurable lead times.  This is the single source of
 * truth for reminder eligibility; the actual dispatching is done by
 * SendBookingReminderJob so that the check and the send are always decoupled.
 *
 * Lead times are resolved in priority order:
 *   1. Per-company DB setting (appointment_settings key: automation.reminders.lead_time_minutes)
 *   2. Config file value (bookingmodule.automation.reminders.lead_time_minutes)
 *   3. Hard-coded default: [1440, 120]
 */
class BookingReminderService
{
    public function __construct(
        protected AppointmentSettingsService $settingsService,
    ) {}

    /**
     * Lead times (in minutes) before schedule start_time at which reminders fire.
     * Reads per-company DB override first, then config, then hard-coded default.
     *
     * @return int[]
     */
    public function reminderLeadTimes(): array
    {
        // Check DB setting first (company-level override)
        $dbValue = $this->settingsService->get('automation.reminders.lead_time_minutes');

        if ($dbValue !== null) {
            $times = is_array($dbValue)
                ? $dbValue
                : array_map('intval', explode(',', (string) $dbValue));

            $times = array_filter(array_map('intval', $times), fn ($v) => $v > 0);
            if (!empty($times)) {
                return array_values($times);
            }
        }

        return (array) config('bookingmodule.automation.reminders.lead_time_minutes', [1440, 120]);
    }

    /**
     * Lead times resolved for an explicit company_id — safe to call from console context.
     *
     * @return int[]
     */
    public function reminderLeadTimesForCompany(int $companyId): array
    {
        $dbValue = $this->settingsService->getForCompany(
            'automation.reminders.lead_time_minutes',
            $companyId
        );

        if ($dbValue !== null) {
            $times = is_array($dbValue)
                ? $dbValue
                : array_map('intval', explode(',', (string) $dbValue));

            $times = array_filter(array_map('intval', $times), fn ($v) => $v > 0);
            if (!empty($times)) {
                return array_values($times);
            }
        }

        return (array) config('bookingmodule.automation.reminders.lead_time_minutes', [1440, 120]);
    }

    /**
     * Whether reminders should trigger after assignment (from DB or config).
     */
    public function remindersOnAssign(): bool
    {
        $dbValue = $this->settingsService->get('automation.reminders.after_assignment');

        if ($dbValue !== null) {
            return (bool) $dbValue;
        }

        return (bool) config('bookingmodule.automation.reminders.triggers.after_assignment', true);
    }

    /**
     * Whether reminders should trigger after assignment — resolved for explicit company_id.
     */
    public function remindersOnAssignForCompany(int $companyId): bool
    {
        $dbValue = $this->settingsService->getForCompany(
            'automation.reminders.after_assignment',
            $companyId
        );

        if ($dbValue !== null) {
            return (bool) $dbValue;
        }

        return (bool) config('bookingmodule.automation.reminders.triggers.after_assignment', true);
    }

    /**
     * Whether reminders should trigger after reschedule (from DB or config).
     */
    public function remindersOnReschedule(): bool
    {
        $dbValue = $this->settingsService->get('automation.reminders.after_reschedule');

        if ($dbValue !== null) {
            return (bool) $dbValue;
        }

        return (bool) config('bookingmodule.automation.reminders.triggers.after_reschedule', true);
    }

    /**
     * Whether reminders should trigger after reschedule — resolved for explicit company_id.
     */
    public function remindersOnRescheduleForCompany(int $companyId): bool
    {
        $dbValue = $this->settingsService->getForCompany(
            'automation.reminders.after_reschedule',
            $companyId
        );

        if ($dbValue !== null) {
            return (bool) $dbValue;
        }

        return (bool) config('bookingmodule.automation.reminders.triggers.after_reschedule', true);
    }

    /**
     * Determine whether a schedule is due for a reminder right now (within a
     * ±tolerance window so the job can run every minute without over-firing).
     *
     * @param  Schedule  $schedule
     * @param  int       $leadMinutes   One of the configured lead times.
     * @param  int       $toleranceMin  Matching window width (default 5 min).
     */
    public function isDue(Schedule $schedule, int $leadMinutes, int $toleranceMin = 5): bool
    {
        if (!$schedule->starts_at && $schedule->date && $schedule->start_time) {
            $startsAt = Carbon::parse($schedule->date . ' ' . $schedule->start_time);
        } elseif ($schedule->starts_at) {
            $startsAt = Carbon::parse($schedule->starts_at);
        } else {
            return false;
        }

        $targetTime = $startsAt->clone()->subMinutes($leadMinutes);
        $now        = Carbon::now();

        return $now->between(
            $targetTime->clone()->subMinutes((int) floor($toleranceMin / 2)),
            $targetTime->clone()->addMinutes((int) ceil($toleranceMin / 2))
        );
    }

    /**
     * Return all schedules that are due for a reminder at a given lead time.
     *
     * When $companyId is provided the query is scoped to that company explicitly
     * (safe for console/queue context where Auth is not set).
     *
     * @param  int       $leadMinutes
     * @param  int|null  $companyId  Optional explicit company scope.
     * @return \Illuminate\Database\Eloquent\Collection<Schedule>
     */
    public function dueSchedules(int $leadMinutes, ?int $companyId = null): \Illuminate\Database\Eloquent\Collection
    {
        $toleranceMin = (int) config('bookingmodule.automation.reminders.tolerance_minutes', 5);

        // Target datetime window: [now + leadMinutes - floor(tol/2), now + leadMinutes + ceil(tol/2)]
        $lowerBound = Carbon::now()->addMinutes($leadMinutes)->subMinutes((int) floor($toleranceMin / 2));
        $upperBound = Carbon::now()->addMinutes($leadMinutes)->addMinutes((int) ceil($toleranceMin / 2));

        $query = Schedule::withoutGlobalScope('company_id')
            ->where('status', 'Approved')
            ->whereNotNull('assigned_to');

        // Scope to company when provided (required for multi-tenant console dispatch).
        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        return $query->where(function ($q) use ($lowerBound, $upperBound) {
                // Prefer starts_at (full datetime) if populated; fall back to date+start_time concat.
                $q->where(function ($qq) use ($lowerBound, $upperBound) {
                    // schedules with a populated starts_at column
                    $qq->whereNotNull('starts_at')
                       ->whereBetween('starts_at', [
                           $lowerBound->toDateTimeString(),
                           $upperBound->toDateTimeString(),
                       ]);
                })->orWhere(function ($qq) use ($lowerBound, $upperBound) {
                    // schedules without starts_at — compare concatenated date+start_time
                    $qq->whereNull('starts_at')
                       ->whereRaw(
                           "CAST(CONCAT(date, ' ', start_time) AS DATETIME) BETWEEN ? AND ?",
                           [$lowerBound->toDateTimeString(), $upperBound->toDateTimeString()]
                       );
                });
            })
            ->get();
    }
}
