<?php

namespace Modules\FSMAvailability\Services;

use Carbon\Carbon;
use Modules\FSMAvailability\Models\FSMAvailabilityException;
use Modules\FSMAvailability\Models\FSMAvailabilityRule;

class AvailabilityService
{
    /**
     * Check whether a worker is available during the given datetime window.
     *
     * Returns an array:
     *   ['available' => bool, 'reason' => string|null, 'exception' => FSMAvailabilityException|null]
     */
    public function checkAvailability(int $userId, Carbon $start, Carbon $end): array
    {
        // 1. Look for an approved exception that overlaps this window.
        $exception = FSMAvailabilityException::where('person_id', $userId)
            ->where('state', 'approved')
            ->where('date_start', '<', $end)
            ->where('date_end', '>', $start)
            ->first();

        if ($exception) {
            $label = FSMAvailabilityException::$reasons[$exception->reason] ?? $exception->reason;
            return [
                'available' => false,
                'reason'    => "Worker has approved {$label} exception: "
                             . $exception->date_start->format('d M Y H:i')
                             . ' – ' . $exception->date_end->format('d M Y H:i'),
                'exception' => $exception,
            ];
        }

        // 2. Check working-hour rules for each day in the window.
        $dayMap = [
            1 => 'mon', 2 => 'tue', 3 => 'wed',
            4 => 'thu', 5 => 'fri', 6 => 'sat', 7 => 'sun',
        ];

        $rules = FSMAvailabilityRule::where('person_id', $userId)
            ->where('active', true)
            ->get()
            ->keyBy('day_of_week');

        // Iterate each calendar day covered by the window.
        $cursor = $start->copy()->startOfDay();
        $finish = $end->copy()->startOfDay();

        while ($cursor <= $finish) {
            $dow  = $dayMap[$cursor->isoWeekday()] ?? null;
            $rule = $dow ? $rules->get($dow) : null;

            // Compute the window slice for this day.
            $dayStart = $cursor->copy()->setTimeFromTimeString($start->format('H:i:s'));
            $dayEnd   = $cursor->copy()->setTimeFromTimeString($end->format('H:i:s'));

            // If window spans multiple days, use full day bounds for non-boundary days.
            if ($cursor->gt($start->copy()->startOfDay())) {
                $dayStart = $cursor->copy()->startOfDay();
            }
            if ($cursor->lt($finish)) {
                $dayEnd = $cursor->copy()->endOfDay();
            }

            if ($rule === null) {
                return [
                    'available' => false,
                    'reason'    => 'Worker has no working-hour rule for '
                                 . (FSMAvailabilityRule::$days[$dow ?? ''] ?? $cursor->format('l')),
                    'exception' => null,
                ];
            }

            // Check the rule window covers the job slice.
            $ruleStart = Carbon::parse($cursor->toDateString() . ' ' . $rule->time_start);
            $ruleEnd   = Carbon::parse($cursor->toDateString() . ' ' . $rule->time_end);

            if ($dayStart < $ruleStart || $dayEnd > $ruleEnd) {
                return [
                    'available' => false,
                    'reason'    => 'Job window falls outside worker\'s scheduled hours on '
                                 . $cursor->format('D d M')
                                 . ' ('  . $rule->time_start . '–' . $rule->time_end . ')',
                    'exception' => null,
                ];
            }

            $cursor->addDay();
        }

        return ['available' => true, 'reason' => null, 'exception' => null];
    }

    /**
     * Return a human-readable availability warning for a worker during an order window,
     * or null when no issues are found (or when the module tables are unavailable).
     */
    public function getOrderWarning(int $userId, ?Carbon $start, ?Carbon $end): ?string
    {
        if ($start === null || $end === null) {
            return null;
        }

        if (!\Illuminate\Support\Facades\Schema::hasTable('fsm_availability_exceptions')) {
            return null;
        }

        $result = $this->checkAvailability($userId, $start, $end);

        return $result['available'] ? null : ('Availability: ' . $result['reason']);
    }

    /**
     * Return all approved exceptions for a worker within a date range.
     *
     * @return \Illuminate\Support\Collection<FSMAvailabilityException>
     */
    public function exceptionsInRange(int $userId, Carbon $from, Carbon $to)
    {
        return FSMAvailabilityException::where('person_id', $userId)
            ->where('state', 'approved')
            ->where('date_start', '<', $to)
            ->where('date_end', '>', $from)
            ->orderBy('date_start')
            ->get();
    }

    /**
     * Return active rules for a worker, keyed by day_of_week string.
     *
     * @return \Illuminate\Support\Collection<FSMAvailabilityRule>
     */
    public function rulesForWorker(int $userId)
    {
        return FSMAvailabilityRule::where('person_id', $userId)
            ->where('active', true)
            ->get()
            ->keyBy('day_of_week');
    }
}
