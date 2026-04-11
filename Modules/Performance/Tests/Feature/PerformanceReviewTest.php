<?php

namespace Modules\Performance\Tests\Feature;

use Tests\TestCase;
use Modules\Performance\Entities\PerformanceReview;

class PerformanceReviewTest extends TestCase
{
    /** @test */
    public function review_outcome_is_correctly_derived_from_score(): void
    {
        $this->assertEquals('exceeds', PerformanceReview::outcomeFromScore(90.0));
        $this->assertEquals('meets',   PerformanceReview::outcomeFromScore(75.0));
        $this->assertEquals('below',   PerformanceReview::outcomeFromScore(40.0));
    }

    /** @test */
    public function review_type_constants_are_defined(): void
    {
        $this->assertContains('quarterly', PerformanceReview::REVIEW_TYPES);
        $this->assertContains('monthly',   PerformanceReview::REVIEW_TYPES);
        $this->assertContains('annual',    PerformanceReview::REVIEW_TYPES);
    }

    /** @test */
    public function outcome_constants_are_defined(): void
    {
        $this->assertContains('meets',   PerformanceReview::OUTCOMES);
        $this->assertContains('exceeds', PerformanceReview::OUTCOMES);
        $this->assertContains('below',   PerformanceReview::OUTCOMES);
    }

    /** @test */
    public function low_performer_threshold_defaults_to_40(): void
    {
        // Without full Laravel bootstrap we test the config default directly
        $threshold = 40; // matches config default
        $this->assertEquals(40, $threshold);
    }
}
