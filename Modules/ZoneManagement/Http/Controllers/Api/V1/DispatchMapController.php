<?php

namespace Modules\ZoneManagement\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\ZoneManagement\Entities\CleanerLocation;
use Modules\ZoneManagement\Services\GeofenceService;

/**
 * API endpoints for the admin dispatch map (live cleaner positions +
 * cleaner-proximity suggestions).
 */
class DispatchMapController extends Controller
{
    public function __construct(private GeofenceService $geofence) {}

    /**
     * Return all cleaners' latest GPS positions.
     *
     * GET /api/v1/gps/dispatch-map
     */
    public function livePositions(): JsonResponse
    {
        $locations = CleanerLocation::latestPerUser()
            ->with('user:id,first_name,last_name,email,profile_image')
            ->get(['cleaner_locations.*']);

        return response()->json($locations);
    }

    /**
     * Suggest the closest available cleaners to a target location.
     *
     * GET /api/v1/gps/nearby-cleaners?lat=...&lng=...&limit=5
     */
    public function nearbySuggestions(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'lat'   => 'required|numeric|between:-90,90',
            'lng'   => 'required|numeric|between:-180,180',
            'limit' => 'nullable|integer|min:1|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $locations = CleanerLocation::latestPerUser()
            ->with('user:id,first_name,last_name,email,profile_image')
            ->get(['cleaner_locations.*']);

        $cleaners = $locations->map(fn($loc) => [
            'user_id' => $loc->user_id,
            'name'    => optional($loc->user)->first_name . ' ' . optional($loc->user)->last_name,
            'lat'     => (float) $loc->lat,
            'lng'     => (float) $loc->lng,
            'last_seen' => $loc->recorded_at,
        ])->toArray();

        $sorted = $this->geofence->sortCleanersByProximity(
            $cleaners,
            (float) $request->lat,
            (float) $request->lng
        );

        $limit = $request->limit ?? 5;

        return response()->json(array_slice($sorted, 0, $limit));
    }
}
