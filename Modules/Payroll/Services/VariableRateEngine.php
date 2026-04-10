<?php

namespace Modules\Payroll\Services;

use Carbon\Carbon;
use Modules\Payroll\Entities\CleanerRateConfig;
use Modules\Payroll\Entities\PublicHoliday;

class VariableRateEngine
{
    /**
     * Australian state codes supported.
     */
    public const AU_STATES = ['NSW', 'VIC', 'QLD', 'SA', 'WA', 'TAS', 'ACT', 'NT'];

    /**
     * Calculate pay for a single job card.
     *
     * @param  int         $userId        The cleaner's user ID
     * @param  Carbon      $jobStart      Job start datetime
     * @param  Carbon      $jobEnd        Job end datetime
     * @param  int         $roomsCleaned  Number of rooms cleaned
     * @param  string|null $contractRef   Optional contract reference
     * @param  string|null $state         AU state code for public holiday check
     * @return array       [
     *   'segments'       => [...],  // split segments
     *   'total_hours'    => float,
     *   'gross_pay'      => float,
     *   'commission'     => float,
     *   'total_pay'      => float,
     *   'rate_type'      => string, // dominant rate
     *   'rate_applied'   => float,
     *   'is_public_holiday' => bool,
     * ]
     */
    public function calculate(
        int $userId,
        Carbon $jobStart,
        Carbon $jobEnd,
        int $roomsCleaned = 0,
        ?string $contractRef = null,
        ?string $state = null
    ): array {
        $config = CleanerRateConfig::effectiveFor($userId, $contractRef);

        if (!$config) {
            // No rate config found – return zeroes
            return $this->emptyResult($jobStart, $jobEnd);
        }

        $isHoliday = PublicHoliday::isHoliday($jobStart, $state);

        $segments = $this->splitIntoSegments($jobStart, $jobEnd, $config, $state);

        $grossPay = 0;
        $totalHours = 0;
        $dominantSegment = null;
        $dominantHours = 0;

        foreach ($segments as &$seg) {
            $hours = $seg['hours'];
            $totalHours += $hours;
            $pay = $hours * $seg['rate'];
            $seg['pay'] = round($pay, 4);
            $grossPay += $pay;

            if ($hours > $dominantHours) {
                $dominantHours = $hours;
                $dominantSegment = $seg;
            }
        }

        unset($seg);

        $commission = 0;
        if ($config->commission_per_room > 0 && $roomsCleaned > 0) {
            $commission = round($config->commission_per_room * $roomsCleaned, 4);
        }

        $totalPay = round($grossPay + $commission, 4);

        return [
            'segments'         => $segments,
            'total_hours'      => round($totalHours, 4),
            'gross_pay'        => round($grossPay, 4),
            'commission'       => $commission,
            'total_pay'        => $totalPay,
            'rate_type'        => $dominantSegment['rate_type'] ?? 'base',
            'rate_applied'     => $dominantSegment['rate'] ?? $config->base_rate,
            'is_public_holiday' => $isHoliday,
        ];
    }

    /**
     * Split a job into rate segments.
     * Handles midnight crossings, rate-boundary crossings (night cutoff),
     * and day-of-week changes.
     */
    protected function splitIntoSegments(
        Carbon $jobStart,
        Carbon $jobEnd,
        CleanerRateConfig $config,
        ?string $state
    ): array {
        $segments = [];
        $current = $jobStart->copy();

        while ($current < $jobEnd) {
            // Determine the next "boundary" – midnight, night cutoff, or end of job
            $nextMidnight = $current->copy()->startOfDay()->addDay(); // start of next day
            $nightCutoff = $this->nightCutoffForDate($current, $config->night_rate_cutoff);

            // Collect all candidate boundaries that are strictly after $current
            $boundaries = [$jobEnd->copy()];

            if ($nextMidnight < $jobEnd) {
                $boundaries[] = $nextMidnight;
            }

            if ($nightCutoff > $current && $nightCutoff < $jobEnd) {
                $boundaries[] = $nightCutoff;
            }

            // Find the earliest boundary
            usort($boundaries, fn($a, $b) => $a->lt($b) ? -1 : 1);
            $segmentEnd = $boundaries[0];

            $hours = $current->diffInMinutes($segmentEnd) / 60.0;

            if ($hours > 0) {
                $rateType = $this->determineRateType($current, $config, $state);
                $rate = $this->rateForType($rateType, $config, $current);

                $segments[] = [
                    'start'     => $current->toDateTimeString(),
                    'end'       => $segmentEnd->toDateTimeString(),
                    'hours'     => round($hours, 6),
                    'rate_type' => $rateType,
                    'rate'      => $rate,
                    'pay'       => 0, // filled after
                ];
            }

            $current = $segmentEnd->copy();
        }

        return $segments;
    }

    /**
     * Determine rate type for a given datetime.
     */
    protected function determineRateType(Carbon $dt, CleanerRateConfig $config, ?string $state): string
    {
        // Public holiday takes top priority
        if (PublicHoliday::isHoliday($dt, $state)) {
            return 'public_holiday';
        }

        $dow = $dt->dayOfWeek; // 0=Sunday, 6=Saturday

        if ($dow === Carbon::SUNDAY) {
            return 'sunday';
        }

        if ($dow === Carbon::SATURDAY) {
            return 'saturday';
        }

        // Night rate check
        $cutoff = $this->nightCutoffForDate($dt, $config->night_rate_cutoff);
        if ($dt >= $cutoff) {
            return 'night';
        }

        return 'base';
    }

    /**
     * Get the effective hourly rate for the given rate type.
     */
    protected function rateForType(string $rateType, CleanerRateConfig $config, Carbon $dt): float
    {
        $base = (float) $config->base_rate;

        switch ($rateType) {
            case 'public_holiday':
                if (!is_null($config->public_holiday_fixed_rate)) {
                    return (float) $config->public_holiday_fixed_rate;
                }
                return $base * (float) $config->public_holiday_multiplier;

            case 'sunday':
                return $base * (float) $config->sunday_multiplier;

            case 'saturday':
                return $base * (float) $config->saturday_multiplier;

            case 'night':
                return $base * (float) $config->night_rate_multiplier;

            default:
                return $base;
        }
    }

    /**
     * Get the night rate cutoff Carbon instance for a given date.
     */
    protected function nightCutoffForDate(Carbon $dt, string $cutoffTime): Carbon
    {
        [$hour, $minute, $second] = array_pad(explode(':', $cutoffTime), 3, '00');

        return $dt->copy()->startOfDay()
            ->setHour((int) $hour)
            ->setMinute((int) $minute)
            ->setSecond((int) $second);
    }

    /**
     * Return an empty/zero result.
     */
    protected function emptyResult(Carbon $jobStart, Carbon $jobEnd): array
    {
        $hours = $jobStart->diffInMinutes($jobEnd) / 60.0;

        return [
            'segments'         => [],
            'total_hours'      => round($hours, 4),
            'gross_pay'        => 0,
            'commission'       => 0,
            'total_pay'        => 0,
            'rate_type'        => 'base',
            'rate_applied'     => 0,
            'is_public_holiday' => false,
        ];
    }
}
