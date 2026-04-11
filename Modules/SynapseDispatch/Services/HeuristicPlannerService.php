<?php

namespace Modules\SynapseDispatch\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Modules\SynapseDispatch\Models\DispatchJob;
use Modules\SynapseDispatch\Models\DispatchWorker;
use Modules\SynapseDispatch\Enums\PlanningStatus;

class HeuristicPlannerService
{
    public function __construct(
        protected WorkerAvailabilityService $availabilityService
    ) {}

    /**
     * Find and return the best available worker for a given job.
     */
    public function plan(DispatchJob $job): ?DispatchWorker
    {
        $candidates = $this->getAvailableWorkers($job);
        $scored = $this->scoreWorkers($candidates, $job);
        return $scored->first();
    }

    /**
     * Return top N ranked workers for a job (used by suggest endpoint).
     *
     * @return Collection<int, array{worker: DispatchWorker, score: float, distance_km: float|null}>
     */
    public function suggest(DispatchJob $job, int $limit = 5): Collection
    {
        $candidates = $this->getAvailableWorkers($job);
        return $this->scoreWorkers($candidates, $job, withScores: true)->take($limit);
    }

    /**
     * Get workers in the same team who are available during the job window.
     */
    private function getAvailableWorkers(DispatchJob $job): Collection
    {
        $query = DispatchWorker::where('is_active', true)
            ->with('location');

        if ($job->team_id) {
            $query->where('team_id', $job->team_id);
        }

        return $query->get()->filter(function (DispatchWorker $worker) use ($job) {
            if (!$job->requested_start_datetime || !$job->requested_duration_minutes) {
                return true;
            }
            if (!$this->isWithinBusinessHours($worker, $job->requested_start_datetime, $job->requested_duration_minutes)) {
                return false;
            }
            return $this->availabilityService->isAvailable(
                $worker,
                $job->requested_start_datetime,
                $job->requested_duration_minutes
            );
        });
    }

    /**
     * Score workers by skill match and proximity; return sorted collection.
     * When $withScores is true, returns array items [{worker, score, distance_km}].
     */
    private function scoreWorkers(Collection $workers, DispatchJob $job, bool $withScores = false): Collection
    {
        $requiredSkills = (array) data_get($job->flex_form_data, 'skills_required', []);
        $jobLat = optional($job->location)->geo_latitude;
        $jobLon = optional($job->location)->geo_longitude;

        $scored = $workers->map(function (DispatchWorker $worker) use ($requiredSkills, $jobLat, $jobLon, $withScores) {
            $score = 0.0;
            $distanceKm = null;

            // Skill match score (0–50 points)
            $workerSkills = (array) ($worker->skills ?? []);
            if (!empty($requiredSkills)) {
                $matched = count(array_intersect($requiredSkills, $workerSkills));
                $score += ($matched / count($requiredSkills)) * 50;
            } else {
                $score += 50; // no skill requirement — full points
            }

            // Proximity score (0–50 points)
            $workerLat = optional($worker->location)->geo_latitude;
            $workerLon = optional($worker->location)->geo_longitude;

            if ($jobLat !== null && $jobLon !== null && $workerLat !== null && $workerLon !== null) {
                $distanceKm = $this->haversineDistance($workerLat, $workerLon, $jobLat, $jobLon);
                // max scoring at 0 km, zero score at ≥100 km
                $proximityScore = max(0, 50 - ($distanceKm / 100) * 50);
                $score += $proximityScore;
            } else {
                $score += 25; // no location data — half points
            }

            if ($withScores) {
                return ['worker' => $worker, 'score' => round($score, 2), 'distance_km' => $distanceKm];
            }

            return ['worker' => $worker, 'score' => $score];
        });

        $sorted = $scored->sortByDesc('score')->values();

        if ($withScores) {
            return $sorted;
        }

        return $sorted->pluck('worker');
    }

    /**
     * Check whether the job window falls within the worker's configured business hours.
     */
    private function isWithinBusinessHours(DispatchWorker $worker, Carbon $start, float $durationMinutes): bool
    {
        $businessHour = $worker->business_hour;
        if (empty($businessHour)) {
            return true; // no restriction
        }

        $dayKey = strtolower($start->format('l')); // e.g. "monday"
        $dayConfig = $businessHour[$dayKey] ?? null;

        if (!$dayConfig) {
            return false; // day not configured => unavailable
        }

        $open  = $dayConfig['open']  ?? null;
        $close = $dayConfig['close'] ?? null;

        if (!$open || !$close) {
            return true; // misconfigured => assume available
        }

        $end = $start->copy()->addMinutes($durationMinutes);

        $openTime  = Carbon::createFromFormat('H:i', $open,  $start->timezone)->setDateFrom($start);
        $closeTime = Carbon::createFromFormat('H:i', $close, $start->timezone)->setDateFrom($start);

        return $start->gte($openTime) && $end->lte($closeTime);
    }

    /**
     * Haversine formula — great-circle distance in kilometres.
     */
    private function haversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadiusKm = 6371.0;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2
           + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadiusKm * $c;
    }
}
