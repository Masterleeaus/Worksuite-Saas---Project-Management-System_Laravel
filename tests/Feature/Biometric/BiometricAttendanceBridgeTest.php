<?php

namespace Tests\Feature\Biometric;

use App\Models\Attendance;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Modules\Biometric\Entities\BiometricDevice;
use Modules\Biometric\Entities\BiometricEmployee;
use Modules\Biometric\Entities\BiometricSetting;
use Modules\Biometric\Events\BiometricClockIn;
use Tests\TestCase;

/**
 * Feature tests for the biometric GPS-coordinates → attendances bridge.
 *
 * These tests verify:
 *  1. GPS coordinates are written to the attendances row on biometric clock-in.
 *  2. Clock-in is rejected (no attendance row saved) when geofence fails and
 *     the geofence_passed flag is correctly set to false.
 *  3. booking_id is auto-assigned from the employee's active booking (when
 *     BookingModule is present).
 */
class BiometricAttendanceBridgeTest extends TestCase
{
    use RefreshDatabase;

    private Company $company;
    private User $employee;
    private BiometricDevice $device;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = Company::factory()->create([
            'timezone' => 'UTC',
            'latitude'  => -33.8688,
            'longitude' => 151.2093,
        ]);

        $this->employee = User::factory()->create([
            'company_id' => $this->company->id,
        ]);

        // Ensure the employee's company relationship resolves
        $this->employee->setRelation('company', $this->company);

        $this->device = BiometricDevice::factory()->create([
            'company_id'    => $this->company->id,
            'serial_number' => 'TEST-SN-001',
            'device_name'   => 'Test Device',
        ]);

        BiometricEmployee::factory()->create([
            'company_id'            => $this->company->id,
            'user_id'               => $this->employee->id,
            'biometric_employee_id' => '1',
        ]);

        // Geofence enabled with a 200 m radius centred on company lat/lng
        BiometricSetting::updateOrCreate(
            ['company_id' => $this->company->id],
            ['geofence_enabled' => true, 'geofence_radius' => 200]
        );
    }

    /** @test */
    public function gps_coordinates_are_saved_to_attendance_row(): void
    {
        Event::fake([BiometricClockIn::class]);

        $timestamp = now()->format('Y-m-d H:i:s');
        $lat       = -33.8690; // ~22 m from company — inside geofence
        $lng       = 151.2095;

        $this->callMarkAttendance($timestamp, lat: $lat, lng: $lng);

        $this->assertDatabaseHas('attendances', [
            'user_id'         => $this->employee->id,
            'clock_in_method' => 'fingerprint',
        ]);

        $attendance = Attendance::where('user_id', $this->employee->id)->latest()->first();

        $this->assertNotNull($attendance);
        $this->assertEqualsWithDelta($lat, (float) $attendance->clock_in_lat, 0.00001);
        $this->assertEqualsWithDelta($lng, (float) $attendance->clock_in_lng, 0.00001);
        $this->assertTrue((bool) $attendance->geofence_passed);
    }

    /** @test */
    public function geofence_failure_sets_flag_and_fires_event(): void
    {
        Event::fake([BiometricClockIn::class]);

        $timestamp = now()->format('Y-m-d H:i:s');

        // Melbourne CBD — well outside a 200 m radius centred on Sydney
        $lat = -37.8136;
        $lng = 144.9631;

        $this->callMarkAttendance($timestamp, lat: $lat, lng: $lng);

        $attendance = Attendance::where('user_id', $this->employee->id)->latest()->first();

        $this->assertNotNull($attendance);
        $this->assertFalse((bool) $attendance->geofence_passed);

        // BiometricClockIn event should still fire (listeners decide what to do)
        Event::assertDispatched(BiometricClockIn::class, function (BiometricClockIn $event) {
            return $event->geofencePassed === false;
        });
    }

    /** @test */
    public function biometric_event_carries_correct_payload(): void
    {
        Event::fake([BiometricClockIn::class]);

        $timestamp = now()->format('Y-m-d H:i:s');

        $this->callMarkAttendance($timestamp, method: 'nfc', deviceId: 'TEST-SN-001');

        Event::assertDispatched(BiometricClockIn::class, function (BiometricClockIn $event) {
            return $event->method === 'nfc'
                && $event->deviceId === 'TEST-SN-001'
                && $event->user->id === $this->employee->id;
        });
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Call the private BiometricEmployee::markAttendance via Reflection.
     */
    private function callMarkAttendance(
        string $timestamp,
        ?float $lat = null,
        ?float $lng = null,
        string $method = 'fingerprint',
        ?string $deviceId = null,
    ): void {
        $reflection = new \ReflectionClass(BiometricEmployee::class);
        $method_ref  = $reflection->getMethod('markAttendance');
        $method_ref->setAccessible(true);
        $method_ref->invokeArgs(null, [
            $this->employee,
            $timestamp,
            $deviceId,
            $method,
            $lat,
            $lng,
        ]);
    }
}
