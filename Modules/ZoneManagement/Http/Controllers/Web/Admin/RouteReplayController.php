<?php

namespace Modules\ZoneManagement\Http\Controllers\Web\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ZoneManagement\Entities\RoutePoint;
use Modules\ZoneManagement\Entities\ZoneCheckIn;
use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Admin route-replay view — shows the GPS path recorded for a booking
 * on an interactive map.
 */
class RouteReplayController extends Controller
{
    use AuthorizesRequests;

    public function show(Request $request, string $bookingId): View
    {
        $this->authorize('zone_view');

        $routePoints = RoutePoint::where('booking_id', $bookingId)
            ->orderBy('sequence')
            ->get(['lat', 'lng', 'accuracy', 'sequence', 'recorded_at']);

        $checkIns = ZoneCheckIn::where('booking_id', $bookingId)
            ->orderBy('checked_in_at')
            ->get();

        return view('zonemanagement::admin.route-replay', compact('routePoints', 'checkIns', 'bookingId'));
    }
}
