<?php

namespace Modules\ZoneManagement\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\ZoneManagement\Entities\RoutePoint;

/**
 * Records and retrieves GPS route data for opt-in route tracking.
 */
class RouteController extends Controller
{
    /**
     * Append a batch of route points for a booking.
     *
     * POST /api/v1/gps/route-points
     * Body: { booking_id, points: [{lat, lng, accuracy?, sequence, recorded_at?}] }
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'booking_id'          => 'required|string',
            'points'              => 'required|array|min:1',
            'points.*.lat'        => 'required|numeric|between:-90,90',
            'points.*.lng'        => 'required|numeric|between:-180,180',
            'points.*.accuracy'   => 'nullable|numeric|min:0',
            'points.*.sequence'   => 'required|integer|min:0',
            'points.*.recorded_at'=> 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $now  = now();

        $rows = array_map(function ($point) use ($request, $user, $now) {
            return [
                'company_id'  => $user->company_id ?? null,
                'user_id'     => $user->id,
                'booking_id'  => $request->booking_id,
                'lat'         => $point['lat'],
                'lng'         => $point['lng'],
                'accuracy'    => $point['accuracy'] ?? null,
                'sequence'    => $point['sequence'],
                'recorded_at' => $point['recorded_at'] ?? $now,
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }, $request->points);

        RoutePoint::insert($rows);

        return response()->json(['inserted' => count($rows)], 201);
    }

    /**
     * Retrieve all route points for a booking (for route-replay admin view).
     *
     * GET /api/v1/gps/route-points?booking_id=xxx
     */
    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $points = RoutePoint::where('booking_id', $request->booking_id)
            ->orderBy('sequence')
            ->get(['id', 'lat', 'lng', 'accuracy', 'sequence', 'recorded_at']);

        return response()->json($points);
    }
}
