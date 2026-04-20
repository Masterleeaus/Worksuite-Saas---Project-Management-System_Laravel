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
            'latitude'      => 'nullable|numeric|between:-90,90',
            'longitude'     => 'nullable|numeric|between:-180,180',
            'lat'           => 'nullable|numeric|between:-90,90',
            'lng'           => 'nullable|numeric|between:-180,180',
            'accuracy'      => 'nullable|numeric|min:0',
            'visit_id'      => 'nullable|integer',
            'booking_id'    => 'nullable',
            'tracking_mode' => 'nullable|in:foreground,background',
            'recorded_at'   => 'nullable|date',
            'sequence'      => 'nullable|integer|min:0',
            'speed'         => 'nullable|numeric|min:0',
            'heading'       => 'nullable|numeric|between:0,360',
        ]);

        $latitude = $request->latitude ?? $request->lat;
        $longitude = $request->longitude ?? $request->lng;

        abort_if($latitude === null || $longitude === null, 422, 'Latitude/longitude are required.');

        $ping = TitanGoLocationPing::create([
            'company_id'    => $request->user()->company_id,
            'worker_id'     => $request->user()->id,
            'visit_id'      => $request->visit_id ?? (is_numeric($request->booking_id) ? (int)$request->booking_id : null),
            'latitude'      => $latitude,
            'longitude'     => $longitude,
            'accuracy'      => $request->accuracy,
            'tracking_mode' => $request->get('tracking_mode', 'foreground'),
            'created_at'    => $request->recorded_at ?? now(),
            'updated_at'    => $request->recorded_at ?? now(),
        ]);

        if ($request->filled('booking_id') && \Illuminate\Support\Facades\Schema::hasTable('titan_go_route_trails')) {
            \Illuminate\Support\Facades\DB::table('titan_go_route_trails')->insert([
                'company_id' => $request->user()->company_id,
                'worker_id' => $request->user()->id,
                'visit_id' => $request->visit_id ?? (is_numeric($request->booking_id) ? (int)$request->booking_id : null),
                'booking_id' => (string)$request->booking_id,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'accuracy' => $request->accuracy,
                'speed' => $request->speed,
                'heading' => $request->heading,
                'sequence' => $request->sequence ?? 0,
                'recorded_at' => $request->recorded_at ?? now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json(['id' => $ping->id], 201);
    }
}
