<?php

namespace Modules\ZoneManagement\Http\Controllers\Web\Admin;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ZoneManagement\Entities\GpsSetting;
use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Admin page for GPS system settings (ping interval, accuracy threshold,
 * data-retention policy, map provider, geofence defaults, etc.).
 */
class GpsSettingsController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $this->authorize('zone_view');

        $user     = $request->user();
        $settings = GpsSetting::forCompany($user->company_id ?? null);

        return view('zonemanagement::admin.gps-settings', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $this->authorize('zone_update');

        $validated = $request->validate([
            'location_ping_interval'       => 'required|integer|min:10|max:600',
            'poor_accuracy_threshold'      => 'required|integer|min:5|max:500',
            'default_geofence_radius'      => 'required|integer|min:10|max:50000',
            'route_data_retention_days'    => 'required|integer|min:1|max:3650',
            'location_data_retention_days' => 'required|integer|min:1|max:3650',
            'route_recording_enabled'      => 'sometimes|boolean',
            'live_tracking_enabled'        => 'sometimes|boolean',
            'map_provider'                 => 'required|in:openstreetmap,google',
            'google_maps_key'              => 'nullable|string|max:255',
        ]);

        $user = $request->user();

        // Checkbox fields are absent when unchecked — default to false
        $validated['route_recording_enabled'] = $request->boolean('route_recording_enabled');
        $validated['live_tracking_enabled']   = $request->boolean('live_tracking_enabled');

        GpsSetting::updateOrCreate(
            ['company_id' => $user->company_id ?? null],
            $validated
        );

        Toastr::success(translate('GPS settings updated successfully.'));

        return back();
    }
}
