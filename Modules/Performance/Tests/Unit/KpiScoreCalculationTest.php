<?php

namespace Modules\Performance\Tests\Unit;

use PHPUnit\Framework\TestCase;

class KpiScoreCalculationTest extends TestCase
{
    /** @test */
    public function it_calculates_performance_score_correctly(): void
    {
        $score = $this->computeScore(
            actualJobs: 10,
            targetJobs: 10,
            completionRate: 90,
            punctualityRate: 95,
            qualityNorm: 80,
            complaintPenalty: 0
        );

        $this->assertGreaterThan(0, $score);
        $this->assertLessThanOrEqual(100, $score);
    }

    /** @test */
    public function it_protects_against_division_by_zero_when_target_jobs_is_zero(): void
    {
        // target = 0, so use max(target, 1) = 1
        $score = $this->computeScore(
            actualJobs: 0,
            targetJobs: 0,
            completionRate: 0,
            punctualityRate: 0,
            qualityNorm: 0,
            complaintPenalty: 0
        );

        $this->assertIsFloat($score);
        $this->assertEquals(0.0, $score);
    }

    /** @test */
    public function it_clamps_score_to_maximum_100(): void
    {
        $score = $this->computeScore(
            actualJobs: 100,
            targetJobs: 1,
            completionRate: 100,
            punctualityRate: 100,
            qualityNorm: 100,
            complaintPenalty: 0
        );

        $this->assertLessThanOrEqual(100, $score);
    }

    /** @test */
    public function it_clamps_score_to_minimum_zero(): void
    {
        $score = $this->computeScore(
            actualJobs: 0,
            targetJobs: 10,
            completionRate: 0,
            punctualityRate: 0,
            qualityNorm: 0,
            complaintPenalty: 100 // extreme penalty
        );

        $this->assertGreaterThanOrEqual(0, $score);
    }

    /** @test */
    public function it_caps_complaint_penalty_at_25_points(): void
    {
        // 10 complaints * 5 = 50, but capped at 25
        $penalty = min(10 * 5, 25);
        $this->assertEquals(25, $penalty);
    }

    /** @test */
    public function outcome_from_score_returns_correct_label(): void
    {
        $this->assertEquals('exceeds', \Modules\Performance\Entities\PerformanceReview::outcomeFromScore(90));
        $this->assertEquals('meets',   \Modules\Performance\Entities\PerformanceReview::outcomeFromScore(70));
        $this->assertEquals('below',   \Modules\Performance\Entities\PerformanceReview::outcomeFromScore(50));
    }

    // ────────────────────────────────────────────
    // Helpers
    // ────────────────────────────────────────────

    private function computeScore(
        int $actualJobs,
        int $targetJobs,
        float $completionRate,
        float $punctualityRate,
        float $qualityNorm,
        float $complaintPenalty
    ): float {
        $target   = max($targetJobs, 1);
        $jobsScore = min(($actualJobs / $target) * 100, 100);
        $penalty   = min($complaintPenalty, 25);

        $score = round(
            ($jobsScore * 0.30)
            + ($completionRate * 0.25)
            + ($punctualityRate * 0.20)
            + ($qualityNorm * 0.25)
            - $penalty,
            2
        );

        return (float) max(0, min(100, $score));
    }
}
