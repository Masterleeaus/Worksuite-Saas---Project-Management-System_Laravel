<?php

namespace Modules\FSMRouteAvailability\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMRoute\Models\FSMRoute;

class RouteBlackoutController extends Controller
{
    /**
     * Sync blackout groups for the given route.
     */
    public function sync(Request $request, int $route_id)
    {
        $route = FSMRoute::findOrFail($route_id);

        $data = $request->validate([
            'group_ids'   => 'nullable|array',
            'group_ids.*' => 'integer|exists:fsm_blackout_groups,id',
        ]);

        $route->blackoutGroups()->sync($data['group_ids'] ?? []);

        return redirect()->back()
            ->with('success', 'Blackout groups synced successfully.');
    }
}
