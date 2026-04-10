<?php

namespace Modules\ZoneManagement\Http\Controllers\Web\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ZoneManagement\Entities\CleanerLocation;
use Modules\ZoneManagement\Entities\GpsSetting;
use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Admin dispatch map — shows live cleaner positions and allows
 * clicking a job-site to see the nearest available cleaners.
 */
class DispatchMapController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $this->authorize('zone_view');

        $user      = $request->user();
        $settings  = GpsSetting::forCompany($user->company_id ?? null);

        $liveLocations = CleanerLocation::latestPerUser()
            ->with('user:id,first_name,last_name,email,profile_image')
            ->get(['cleaner_locations.*']);

        return view('zonemanagement::admin.dispatch-map', compact('liveLocations', 'settings'));
    }
}
