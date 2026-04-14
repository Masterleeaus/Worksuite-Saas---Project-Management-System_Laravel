<?php

namespace Modules\TitanGo\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TitanGo\Models\TitanGoLocationPing;

/**
 * LocationPingController
 *
 * Accepts continuous GPS heartbeats from Titan Go during dispatched/on-site visits.
 *
 * POST /api/fsm/v1/location/ping
 *
 * Body: {
 *   "visit_id":      123,          // optional — null when worker is in transit
 *   "latitude":      -33.8688,
 *   "longitude":     151.2093,
 *   "accuracy":      5.0,          // metres
 *   "tracking_mode": "foreground"  // foreground | background
 * }
 */
class LocationPingController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'latitude'      => 'required|numeric|between:-90,90',
            'longitude'     => 'required|numeric|between:-180,180',
            'accuracy'      => 'nullable|numeric|min:0',
            'visit_id'      => 'nullable|integer',
            'tracking_mode' => 'nullable|in:foreground,background',
        ]);

        $ping = TitanGoLocationPing::create([
            'company_id'    => $request->user()->company_id,
            'worker_id'     => $request->user()->id,
            'visit_id'      => $request->visit_id,
            'latitude'      => $request->latitude,
            'longitude'     => $request->longitude,
            'accuracy'      => $request->accuracy,
            'tracking_mode' => $request->get('tracking_mode', 'foreground'),
        ]);

        return response()->json(['id' => $ping->id], 201);
    }
}
