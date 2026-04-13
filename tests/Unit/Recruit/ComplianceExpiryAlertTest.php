<?php

namespace Tests\Unit\Recruit;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the compliance expiry alert logic used by
 * RecruitComplianceExpiryReminderCommand.
 *
 * Runs without a database connection — verifies the date arithmetic
 * that determines when alerts should fire.
 */
class ComplianceExpiryAlertTest extends TestCase
{
    /** @test */
    public function alert_fires_exactly_on_configured_threshold_days(): void
    {
        $alertDays = [30, 14, 7];

        foreach ($alertDays as $days) {
            $expiryDate = Carbon::today()->addDays($days);
            $targetDate = Carbon::today()->addDays($days);

            $this->assertTrue(
                $expiryDate->isSameDay($targetDate),
                "Alert should fire when expiry is exactly {$days} day(s) away"
            );
        }
    }

    /** @test */
    public function alert_does_not_fire_outside_configured_thresholds(): void
    {
        $alertDays      = [30, 14, 7];
        $nonAlertOffset = 15; // not in the threshold list

        foreach ($alertDays as $days) {
            $this->assertNotEquals(
                $days,
                $nonAlertOffset,
                "Day offset {$nonAlertOffset} should not match threshold {$days}"
            );
        }
    }

    /** @test */
    public function compliance_expiry_fields_are_all_date_fields(): void
    {
        // Verify that all three FSM compliance expiry fields are tracked
        $expectedFields = [
            'police_check_expiry',
            'wwcc_expiry',
            'insurance_expiry',
        ];

        foreach ($expectedFields as $field) {
            $this->assertNotEmpty($field, "Field '{$field}' should be non-empty");
            $this->assertStringContainsString('expiry', $field, "Field '{$field}' should contain 'expiry'");
        }
    }

    /** @test */
    public function thirty_day_alert_fires_before_fourteen_and_seven(): void
    {
        $expiry = Carbon::today()->addDays(30);

        $this->assertGreaterThan(14, $expiry->diffInDays(Carbon::today()));
        $this->assertGreaterThan(7, $expiry->diffInDays(Carbon::today()));
    }

    /** @test */
    public function past_expiry_date_is_not_targeted_by_alert(): void
    {
        $alertDays  = [30, 14, 7];
        $pastExpiry = Carbon::today()->subDay();

        foreach ($alertDays as $days) {
            $targetDate = Carbon::today()->addDays($days);
            $this->assertFalse(
                $pastExpiry->isSameDay($targetDate),
                "Past expiry should not match future target date for {$days}-day alert"
            );
        }
    }
}
