<?php

namespace Modules\ZoneManagement\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\ZoneManagement\Entities\CleanerLocation;
use Modules\ZoneManagement\Entities\GpsSetting;
use Modules\ZoneManagement\Entities\RoutePoint;
use Modules\ZoneManagement\Entities\Zone;
use Modules\ZoneManagement\Entities\ZoneCheckIn;
use Modules\ZoneManagement\Services\GeofenceService;

/**
 * Handles GPS check-in and check-out for field cleaners.
 *
 * Endpoints consumed by the PWA field app.
 */
class CheckInController extends Controller
{
    public function __construct(private GeofenceService $geofence) {}

    /**
     * Verify whether the given coordinates are within the geofence for the
     * zone (job site) and return distance info — used by the PWA to decide
     * whether to enable the Check-In button.
     *
     * POST /api/v1/gps/geofence-check
     * Body: { zone_id, lat, lng }
     */
    public function geofenceCheck(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'zone_id' => 'required|string',
            'lat'     => 'required|numeric|between:-90,90',
            'lng'     => 'required|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $zone = Zone::withoutGlobalScope('translate')->find($request->zone_id);
        if (!$zone) {
            return response()->json(['message' => 'Zone not found.'], 404);
        }

        $result = $this->geofence->isWithinZone($zone, (float) $request->lat, (float) $request->lng);

        return response()->json([
            'zone_id'    => $zone->id,
            'zone_name'  => $zone->name,
            'within'     => $result['within'],
            'distance_m' => $result['distance_m'],
            'radius_m'   => $result['radius_m'] ?? $zone->radius,
        ]);
    }

    /**
     * Record a GPS check-in event.
     *
     * POST /api/v1/gps/check-in
     * Body: { zone_id, booking_id?, lat, lng, accuracy }
     */
    public function checkIn(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'zone_id'    => 'required|string',
            'booking_id' => 'nullable|string',
            'lat'        => 'required|numeric|between:-90,90',
            'lng'        => 'required|numeric|between:-180,180',
            'accuracy'   => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user   = Auth::user();
        $companyId = $user->company_id ?? null;

        $settings = GpsSetting::forCompany($companyId);
        $poorThreshold = $settings->poor_accuracy_threshold ?? 50;

        $zone = Zone::withoutGlobalScope('translate')->find($request->zone_id);
        if (!$zone) {
            return response()->json(['message' => 'Zone not found.'], 404);
        }

        $accuracy = $request->accuracy !== null ? (float) $request->accuracy : null;
        $isPoorAccuracy = $accuracy !== null && $accuracy > $poorThreshold;
        $isVerified = !$isPoorAccuracy;

        $geofenceResult = $this->geofence->isWithinZone($zone, (float) $request->lat, (float) $request->lng);
        $withinGeofence = $geofenceResult['within'];

        // Close any existing open check-in for this user before opening a new one
        ZoneCheckIn::where('user_id', $user->id)
            ->whereNull('checked_out_at')
            ->update(['checked_out_at' => now(), 'notes' => 'auto-closed on new check-in']);

        $checkIn = ZoneCheckIn::create([
            'company_id'       => $companyId,
            'booking_id'       => $request->booking_id,
            'user_id'          => $user->id,
            'check_in_lat'     => $request->lat,
            'check_in_lng'     => $request->lng,
            'check_in_accuracy'=> $accuracy,
            'checked_in_at'    => now(),
            'is_verified'      => $isVerified,
            'within_geofence'  => $withinGeofence,
        ]);

        // Broadcast event for admin dispatch map (if broadcasting is configured)
        if (class_exists(\Illuminate\Support\Facades\Event::class)) {
            try {
                event(new \Modules\ZoneManagement\Events\CleanerCheckedIn($checkIn));
            } catch (\Throwable) {
                // Broadcasting not configured — ignore silently
            }
        }

        return response()->json([
            'check_in_id'    => $checkIn->id,
            'checked_in_at'  => $checkIn->checked_in_at,
            'is_verified'    => $isVerified,
            'within_geofence'=> $withinGeofence,
            'distance_m'     => $geofenceResult['distance_m'],
            'warning'        => $isPoorAccuracy
                ? 'GPS accuracy is poor. Check-in recorded as unverified.'
                : null,
        ], 201);
    }

    /**
     * Record a GPS check-out event.
     *
     * POST /api/v1/gps/check-out
     * Body: { check_in_id, lat, lng, accuracy }
     */
    public function checkOut(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'check_in_id' => 'required|integer|exists:zone_check_ins,id',
            'lat'         => 'required|numeric|between:-90,90',
            'lng'         => 'required|numeric|between:-180,180',
            'accuracy'    => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user    = Auth::user();
        $checkIn = ZoneCheckIn::where('id', $request->check_in_id)
            ->where('user_id', $user->id)
            ->whereNull('checked_out_at')
            ->first();

        if (!$checkIn) {
            return response()->json(['message' => 'Active check-in not found.'], 404);
        }

        $checkIn->update([
            'check_out_lat'     => $request->lat,
            'check_out_lng'     => $request->lng,
            'check_out_accuracy'=> $request->accuracy,
            'checked_out_at'    => now(),
        ]);

        return response()->json([
            'check_in_id'    => $checkIn->id,
            'checked_out_at' => $checkIn->checked_out_at,
        ]);
    }

    /**
     * Return the check-in history (audit log) for a booking.
     *
     * GET /api/v1/gps/check-ins?booking_id=xxx
     */
    public function history(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $records = ZoneCheckIn::where('booking_id', $request->booking_id)
            ->orderBy('checked_in_at')
            ->get();

        return response()->json($records);
    }
}
