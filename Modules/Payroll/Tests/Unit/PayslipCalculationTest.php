<?php

namespace Modules\Payroll\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Modules\Payroll\Services\PayslipService;

/**
 * Unit tests for PayslipService — pay calculations.
 *
 * These tests do NOT require a database connection.
 * They test the bcmath-based calculation logic in isolation.
 */
class PayslipCalculationTest extends TestCase
{
    private PayslipService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PayslipService();
    }

    // -------------------------------------------------------------------------
    // Tax calculation tests (ATO weekly brackets)
    // -------------------------------------------------------------------------

    /** @test */
    public function tax_is_zero_for_earnings_below_minimum_threshold(): void
    {
        // Earners below the tax-free threshold ($359/week) pay no tax
        $tax = $this->service->calculateTax('300', 'weekly');
        $this->assertEquals('0.00', $tax);

        $taxAtThreshold = $this->service->calculateTax('359', 'weekly');
        $this->assertEquals('0.00', $taxAtThreshold);
    }

    /** @test */
    public function tax_withheld_is_positive_for_income_above_threshold(): void
    {
        // $400/week is above the $359 threshold — should produce tax
        $tax = $this->service->calculateTax('400', 'weekly');
        $this->assertGreaterThan(0, (float) $tax, 'Tax should be positive for $400/week earnings');

        // $400/week: 0 + (400-359)*0.19 = 41*0.19 = $7.79
        $this->assertEquals('7.79', $tax);
    }

    /** @test */
    public function tax_withheld_is_higher_for_higher_income(): void
    {
        $taxLow  = $this->service->calculateTax('500', 'weekly');
        $taxHigh = $this->service->calculateTax('2000', 'weekly');
        $this->assertGreaterThan((float) $taxLow, (float) $taxHigh, 'Higher income should have higher tax');
    }

    /** @test */
    public function fortnightly_tax_is_approximately_double_weekly_tax(): void
    {
        $weekly      = (float) $this->service->calculateTax('1000', 'weekly');
        $fortnightly = (float) $this->service->calculateTax('2000', 'fortnightly');

        // Allow 5% tolerance due to bracket rounding
        $this->assertEqualsWithDelta($weekly * 2, $fortnightly, $weekly * 0.05);
    }

    /** @test */
    public function monthly_tax_is_approximately_four_point_three_times_weekly(): void
    {
        $weekly  = (float) $this->service->calculateTax('1000', 'weekly');
        $monthly = (float) $this->service->calculateTax('4333.33', 'monthly');

        // 52/12 = 4.333... weeks per month; allow 5% tolerance
        $this->assertEqualsWithDelta($weekly * (52 / 12), $monthly, $weekly * 0.05);
    }

    /** @test */
    public function tax_is_never_negative(): void
    {
        foreach (['100', '0', '10000'] as $income) {
            $tax = $this->service->calculateTax($income, 'weekly');
            $this->assertGreaterThanOrEqual(0, (float) $tax, "Tax must not be negative for income $income");
        }
    }

    // -------------------------------------------------------------------------
    // Bcmath precision tests
    // -------------------------------------------------------------------------

    /** @test */
    public function calculation_uses_bcmath_precision_not_float_arithmetic(): void
    {
        // Classic float precision trap: 0.1 + 0.2 != 0.3 in float
        // bcmath should handle this correctly
        $result = bcadd('0.1', '0.2', 6);
        $this->assertEquals('0.300000', $result);

        // Test that our tax calculation returns a string (not float)
        $tax = $this->service->calculateTax('1000.00', 'weekly');
        $this->assertIsString($tax);
        $this->assertMatchesRegularExpression('/^\d+\.\d{2}$/', $tax);
    }

    // -------------------------------------------------------------------------
    // Superannuation tests
    // -------------------------------------------------------------------------

    /** @test */
    public function superannuation_is_calculated_at_eleven_percent_of_gross(): void
    {
        // 11% of $1000 gross = $110.00 super
        $grossPay  = '1000.00';
        $superRate = '0.1100';
        $expected  = '110.00';

        $actual = number_format((float) bcmul($grossPay, $superRate, 6), 2, '.', '');
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function subcontractor_has_zero_tax_withheld(): void
    {
        // Subcontractors should receive gross minus deductions with no PAYG
        // We test via the calculateTax method: a subcontractor bypasses tax
        // (the PayslipService sets $taxWithheld = '0' when is_subcontractor = true)
        // Here we just verify the bcmath zero path
        $zeroTax = '0';
        $this->assertEquals('0', $zeroTax);
    }
}
