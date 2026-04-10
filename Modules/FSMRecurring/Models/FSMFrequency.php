<?php

namespace Modules\FSMRecurring\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * FSMFrequency – a single recurrence rule (ported from Odoo fsm.frequency).
 *
 * Supports daily / weekly / monthly / yearly frequencies with optional
 * by-weekday, by-month-day, by-month, and by-set-position filters.
 */
class FSMFrequency extends Model
{
    protected $table = 'fsm_frequencies';

    protected $fillable = [
        'company_id', 'name', 'active',
        'interval', 'interval_type', 'is_exclusive',
        'use_bymonthday', 'month_day',
        'use_byweekday',
        'weekday_mo', 'weekday_tu', 'weekday_we', 'weekday_th',
        'weekday_fr', 'weekday_sa', 'weekday_su',
        'use_bymonth',
        'month_jan', 'month_feb', 'month_mar', 'month_apr',
        'month_may', 'month_jun', 'month_jul', 'month_aug',
        'month_sep', 'month_oct', 'month_nov', 'month_dec',
        'use_setpos', 'set_pos',
    ];

    protected $casts = [
        'active' => 'boolean',
        'is_exclusive' => 'boolean',
        'use_bymonthday' => 'boolean',
        'use_byweekday' => 'boolean',
        'weekday_mo' => 'boolean', 'weekday_tu' => 'boolean', 'weekday_we' => 'boolean',
        'weekday_th' => 'boolean', 'weekday_fr' => 'boolean',
        'weekday_sa' => 'boolean', 'weekday_su' => 'boolean',
        'use_bymonth' => 'boolean',
        'month_jan' => 'boolean', 'month_feb' => 'boolean', 'month_mar' => 'boolean',
        'month_apr' => 'boolean', 'month_may' => 'boolean', 'month_jun' => 'boolean',
        'month_jul' => 'boolean', 'month_aug' => 'boolean', 'month_sep' => 'boolean',
        'month_oct' => 'boolean', 'month_nov' => 'boolean', 'month_dec' => 'boolean',
        'use_setpos' => 'boolean',
        'interval' => 'integer',
        'month_day' => 'integer',
        'set_pos' => 'integer',
    ];

    // Interval type constants
    const INTERVAL_DAILY   = 'daily';
    const INTERVAL_WEEKLY  = 'weekly';
    const INTERVAL_MONTHLY = 'monthly';
    const INTERVAL_YEARLY  = 'yearly';

    public static array $intervalTypes = [
        self::INTERVAL_DAILY   => 'Daily',
        self::INTERVAL_WEEKLY  => 'Weekly',
        self::INTERVAL_MONTHLY => 'Monthly',
        self::INTERVAL_YEARLY  => 'Yearly',
    ];

    public function frequencySets()
    {
        return $this->belongsToMany(FSMFrequencySet::class, 'fsm_frequency_set_rule', 'frequency_id', 'frequency_set_id');
    }

    /**
     * Generate all occurrence dates between $start and $until (inclusive).
     * Returns an array of Carbon instances.
     *
     * When use_setpos is enabled for monthly/yearly rules the method enumerates
     * all days that satisfy the weekday/month-day filters within each period and
     * then picks the Nth one (positive = from start, negative = from end).
     * This supports patterns such as "first Monday of the month" (monthly,
     * weekday_mo=true, set_pos=1) or "last Friday" (set_pos=-1).
     */
    public function getOccurrences(Carbon $start, Carbon $until): array
    {
        if ($this->use_setpos && $this->set_pos !== null
            && in_array($this->interval_type, [self::INTERVAL_MONTHLY, self::INTERVAL_YEARLY], true)
        ) {
            return $this->getOccurrencesBySetPos($start, $until);
        }

        $dates = [];
        $current = $start->copy();
        $interval = max(1, (int) $this->interval);

        while ($current->lessThanOrEqualTo($until)) {
            if ($this->matchesDate($current)) {
                $dates[] = $current->copy();
            }
            $current = $this->advance($current, $interval);
        }

        return $dates;
    }

    /**
     * Period-based occurrence generation for monthly/yearly rules that use
     * BYSETPOS semantics (e.g. "first Monday of the month", "last Friday").
     *
     * For each period (month or year) the method collects all days that satisfy
     * the by-weekday / by-month-day / by-month filters and then selects the
     * element at position set_pos (1-based from start; -1-based from end).
     */
    private function getOccurrencesBySetPos(Carbon $start, Carbon $until): array
    {
        $dates   = [];
        $interval = max(1, (int) $this->interval);
        $setPos   = (int) $this->set_pos;

        // Align period cursor to the start of the first period containing $start
        $periodStart = $this->interval_type === self::INTERVAL_MONTHLY
            ? $start->copy()->startOfMonth()
            : $start->copy()->startOfYear();

        while ($periodStart->lessThanOrEqualTo($until)) {
            $periodEnd = $this->interval_type === self::INTERVAL_MONTHLY
                ? $periodStart->copy()->endOfMonth()
                : $periodStart->copy()->endOfYear();

            // Collect all days within this period that satisfy day-level filters
            $periodDates = [];
            $day = $periodStart->copy();
            while ($day->lessThanOrEqualTo($periodEnd)) {
                if ($this->matchesDate($day)) {
                    $periodDates[] = $day->copy();
                }
                $day->addDay();
            }

            // Apply set-position selector.
            // Positive set_pos is 1-based from the start (1 = first, 2 = second …).
            // Negative set_pos counts from the end (−1 = last, −2 = second-to-last …).
            $count = count($periodDates);
            if ($count > 0) {
                $idx = $setPos > 0 ? $setPos - 1 : $count + $setPos;
                if ($idx >= 0 && $idx < $count) {
                    $target = $periodDates[$idx];
                    if ($target->greaterThanOrEqualTo($start) && $target->lessThanOrEqualTo($until)) {
                        $dates[] = $target;
                    }
                }
            }

            // Advance to the next period
            if ($this->interval_type === self::INTERVAL_MONTHLY) {
                $periodStart->addMonths($interval);
            } else {
                $periodStart->addYears($interval);
            }
        }

        return $dates;
    }

    /**
     * Advance $date by one step of this rule's interval.
     */
    private function advance(Carbon $date, int $interval): Carbon
    {
        return match ($this->interval_type) {
            self::INTERVAL_DAILY   => $date->addDays($interval),
            self::INTERVAL_WEEKLY  => $date->addWeeks($interval),
            self::INTERVAL_MONTHLY => $date->addMonths($interval),
            self::INTERVAL_YEARLY  => $date->addYears($interval),
            default                => $date->addDays($interval),
        };
    }

    /**
     * Check whether a given date satisfies all enabled by-* filters.
     */
    public function matchesDate(Carbon $date): bool
    {
        if ($this->use_byweekday && !$this->matchesWeekday($date)) {
            return false;
        }

        if ($this->use_bymonthday && $this->month_day) {
            if ($date->day !== (int) $this->month_day) {
                return false;
            }
        }

        if ($this->use_bymonth && !$this->matchesMonth($date)) {
            return false;
        }

        return true;
    }

    private function matchesWeekday(Carbon $date): bool
    {
        $map = [
            1 => 'weekday_mo',
            2 => 'weekday_tu',
            3 => 'weekday_we',
            4 => 'weekday_th',
            5 => 'weekday_fr',
            6 => 'weekday_sa',
            7 => 'weekday_su',
        ];

        $col = $map[$date->dayOfWeekIso] ?? null;
        return $col && (bool) $this->{$col};
    }

    private function matchesMonth(Carbon $date): bool
    {
        $monthCols = [
            1 => 'month_jan', 2 => 'month_feb',  3 => 'month_mar',
            4 => 'month_apr', 5 => 'month_may',  6 => 'month_jun',
            7 => 'month_jul', 8 => 'month_aug',  9 => 'month_sep',
            10 => 'month_oct', 11 => 'month_nov', 12 => 'month_dec',
        ];

        $col = $monthCols[$date->month] ?? null;
        return $col && (bool) $this->{$col};
    }
}
