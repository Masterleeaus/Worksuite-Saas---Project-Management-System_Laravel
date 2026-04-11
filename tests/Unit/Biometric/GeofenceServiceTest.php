<?php

namespace Tests\Unit\Biometric;

use Modules\Biometric\Services\GeofenceService;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the GeofenceService Haversine distance calculation.
 *
 * Intentionally extends PHPUnit\Framework\TestCase (not Laravel's TestCase)
 * because all tests exercise the pure Haversine math in
 * GeofenceService::haversineDistanceMetres(), which has no database or
 * container dependencies.
 */
class GeofenceServiceTest extends TestCase
{
    private GeofenceService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new GeofenceService();
    }

    /** @test */
    public function haversine_returns_zero_for_identical_coordinates(): void
    {
        $distance = $this->service->haversineDistanceMetres(
            -33.8688,
            151.2093,
            -33.8688,
            151.2093
        );

        $this->assertEqualsWithDelta(0.0, $distance, 0.001);
    }

    /** @test */
    public function haversine_returns_correct_distance_between_known_points(): void
    {
        // Sydney Opera House → Sydney Harbour Bridge is approx 1.55 km
        $distance = $this->service->haversineDistanceMetres(
            -33.8568,  // Opera House lat
            151.2153,  // Opera House lng
            -33.8523,  // Harbour Bridge lat
            151.2108   // Harbour Bridge lng
        );

        // Expect roughly 650–750 m (straight line)
        $this->assertGreaterThan(500, $distance);
        $this->assertLessThan(1000, $distance);
    }

    /** @test */
    public function haversine_is_symmetric(): void
    {
        $a = $this->service->haversineDistanceMetres(51.5074, -0.1278, 48.8566, 2.3522);
        $b = $this->service->haversineDistanceMetres(48.8566, 2.3522, 51.5074, -0.1278);

        $this->assertEqualsWithDelta($a, $b, 0.001);
    }
}
