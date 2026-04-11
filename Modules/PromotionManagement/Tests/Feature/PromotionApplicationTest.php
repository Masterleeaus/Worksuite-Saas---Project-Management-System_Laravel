<?php

namespace Modules\PromotionManagement\Tests\Feature;

use Modules\PromotionManagement\Services\PromotionService;
use Tests\TestCase;

/**
 * Feature tests for PromotionManagement coupon/discount application.
 *
 * These tests focus on the PromotionService public API, simulating realistic
 * call scenarios without requiring a provisioned database (stubs are used
 * where DB calls are unavoidable).
 */
class PromotionApplicationTest extends TestCase
{
    private PromotionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(PromotionService::class);
    }

    // -----------------------------------------------------------------------
    // Invalid / non-existent coupon
    // -----------------------------------------------------------------------

    /** @test */
    public function applying_unknown_coupon_code_returns_failure(): void
    {
        $result = $this->service->apply('DOES_NOT_EXIST_99', null, 150.0);

        $this->assertFalse($result['success']);
        $this->assertSame(0.0, $result['discount_amount']);
        $this->assertNull($result['discount_id']);
        $this->assertNull($result['coupon_id']);
        $this->assertNotEmpty($result['message']);
    }

    // -----------------------------------------------------------------------
    // Auto-apply: graceful null return when no promo configured
    // -----------------------------------------------------------------------

    /** @test */
    public function auto_apply_returns_null_gracefully_without_active_promos(): void
    {
        $result = $this->service->autoApply(null, 100.0, 'regular', null);

        $this->assertNull($result);
    }

    // -----------------------------------------------------------------------
    // Response structure contract
    // -----------------------------------------------------------------------

    /** @test */
    public function apply_response_always_contains_required_keys(): void
    {
        $result = $this->service->apply('ANY_CODE', null, 0.0);

        $this->assertArrayHasKey('success', $result);
        $this->assertArrayHasKey('message', $result);
        $this->assertArrayHasKey('discount_amount', $result);
        $this->assertArrayHasKey('discount_id', $result);
        $this->assertArrayHasKey('coupon_id', $result);
    }

    /** @test */
    public function discount_amount_is_always_a_float(): void
    {
        $result = $this->service->apply('ANY_CODE', null, 50.0);

        $this->assertIsFloat($result['discount_amount']);
    }

    // -----------------------------------------------------------------------
    // recordRedemption is safe to call even without DB
    // -----------------------------------------------------------------------

    /** @test */
    public function record_redemption_does_not_throw_for_missing_id(): void
    {
        // Should silently fail / not throw — the DB will have no matching row
        try {
            $this->service->recordRedemption('non-existent-uuid');
            $this->assertTrue(true); // reached here without exception
        } catch (\Throwable $e) {
            $this->fail('recordRedemption threw unexpectedly: ' . $e->getMessage());
        }
    }
}
