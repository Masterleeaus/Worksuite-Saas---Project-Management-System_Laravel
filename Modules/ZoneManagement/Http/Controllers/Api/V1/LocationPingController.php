<?php

namespace Modules\ZoneManagement\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\ZoneManagement\Entities\CleanerLocation;
use Modules\ZoneManagement\Entities\GpsSetting;

/**
 * Receives periodic GPS pings from the PWA field app while a cleaner
 * is checked in to an active job (live tracking).
 */
class LocationPingController extends Controller
{
    /**
     * Store a live location ping.
     *
     * POST /api/v1/gps/location-ping
     * Body: { lat, lng, accuracy?, speed?, heading?, booking_id?, recorded_at? }
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'lat'         => 'required|numeric|between:-90,90',
            'lng'         => 'required|numeric|between:-180,180',
            'accuracy'    => 'nullable|numeric|min:0',
            'speed'       => 'nullable|numeric|min:0',
            'heading'     => 'nullable|numeric|between:0,360',
            'booking_id'  => 'nullable|string',
            'recorded_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();

        CleanerLocation::create([
            'company_id'  => $user->company_id ?? null,
            'user_id'     => $user->id,
            'booking_id'  => $request->booking_id,
            'lat'         => $request->lat,
            'lng'         => $request->lng,
            'accuracy'    => $request->accuracy,
            'speed'       => $request->speed,
            'heading'     => $request->heading,
            'recorded_at' => $request->recorded_at ?? now(),
        ]);

        // Return the configured ping interval so the PWA can self-adjust
        $settings     = GpsSetting::forCompany($user->company_id ?? null);
        $pingInterval = $settings->location_ping_interval ?? 60;

        return response()->json([
            'ping_interval_seconds' => $pingInterval,
        ], 201);
    }

    /**
     * Return the latest location for all cleaners currently checked in
     * (for the admin dispatch map).
     *
     * GET /api/v1/gps/live-locations
     */
    public function liveLocations(Request $request): JsonResponse
    {
        $locations = CleanerLocation::latestPerUser()
            ->with('user:id,first_name,last_name,email,profile_image')
            ->get(['cleaner_locations.*']);

        return response()->json($locations);
    }

    /**
     * Return location history for a specific user (for travel-time calc or review).
     *
     * GET /api/v1/gps/location-history?user_id=1&from=...&to=...
     */
    public function history(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'from'    => 'nullable|date',
            'to'      => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $query = CleanerLocation::where('user_id', $request->user_id)
            ->when($request->from, fn($q) => $q->where('recorded_at', '>=', $request->from))
            ->when($request->to,   fn($q) => $q->where('recorded_at', '<=', $request->to))
            ->orderBy('recorded_at');

        return response()->json($query->get());
    }
}
