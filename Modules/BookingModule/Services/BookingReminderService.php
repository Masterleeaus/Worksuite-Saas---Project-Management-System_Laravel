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
 */
class BookingReminderService
{
    /**
     * Lead times (in minutes) before schedule start_time at which reminders fire.
     * Pulled from config — defaults to 24 h and 2 h.
     *
     * @return int[]
     */
    public function reminderLeadTimes(): array
    {
        return (array) config('bookingmodule.automation.reminders.lead_time_minutes', [1440, 120]);
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
     * @param  int  $leadMinutes
     * @return \Illuminate\Database\Eloquent\Collection<Schedule>
     */
    public function dueSchedules(int $leadMinutes): \Illuminate\Database\Eloquent\Collection
    {
        $toleranceMin = (int) config('bookingmodule.automation.reminders.tolerance_minutes', 5);

        // Target datetime window: [now + leadMinutes - floor(tol/2), now + leadMinutes + ceil(tol/2)]
        $lowerBound = Carbon::now()->addMinutes($leadMinutes)->subMinutes((int) floor($toleranceMin / 2));
        $upperBound = Carbon::now()->addMinutes($leadMinutes)->addMinutes((int) ceil($toleranceMin / 2));

        return Schedule::query()
            ->where('status', 'Approved')
            ->whereNotNull('assigned_to')
            ->where(function ($q) use ($lowerBound, $upperBound) {
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
