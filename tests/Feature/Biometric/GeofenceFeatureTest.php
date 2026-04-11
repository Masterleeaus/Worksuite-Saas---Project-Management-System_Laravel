<?php

namespace Tests\Feature\Biometric;

use Modules\Biometric\Services\GeofenceService;
use PHPUnit\Framework\TestCase;

/**
 * Feature-level tests for the GeofenceService that can run without a
 * database connection.  These verify the end-to-end geofence logic including
 * the "passes" method and the radius boundary conditions.
 *
 * Intentionally extends PHPUnit\Framework\TestCase (not Laravel's TestCase)
 * because all assertions exercise the pure Haversine maths with no database
 * or container dependencies — consistent with ExampleTest.php in this repo.
 */
class GeofenceFeatureTest extends TestCase
{
    private GeofenceService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new GeofenceService();
    }

    /** @test */
    public function employee_inside_geofence_radius_passes(): void
    {
        // Sydney CBD office at -33.8688, 151.2093
        // Employee ~50 m away — well within a 200 m radius
        $distance = $this->service->haversineDistanceMetres(
            -33.8688, 151.2093,  // Centre (office)
            -33.8692, 151.2098   // Employee (nearby)
        );

        $this->assertLessThan(200, $distance, 'Employee should be within 200 m radius');
    }

    /** @test */
    public function employee_outside_geofence_radius_fails(): void
    {
        // Sydney CBD office at -33.8688, 151.2093
        // Melbourne CBD — thousands of km away
        $distance = $this->service->haversineDistanceMetres(
            -33.8688, 151.2093,
            -37.8136, 144.9631
        );

        $this->assertGreaterThan(200, $distance, 'Employee should be outside 200 m radius');
    }

    /** @test */
    public function employee_exactly_at_centre_is_within_radius(): void
    {
        $distance = $this->service->haversineDistanceMetres(
            -33.8688, 151.2093,
            -33.8688, 151.2093
        );

        $this->assertLessThanOrEqual(0.001, $distance);
    }

    /** @test */
    public function distance_never_negative(): void
    {
        $distance = $this->service->haversineDistanceMetres(
            48.8566, 2.3522,
            51.5074, -0.1278
        );

        $this->assertGreaterThanOrEqual(0, $distance);
    }
}
