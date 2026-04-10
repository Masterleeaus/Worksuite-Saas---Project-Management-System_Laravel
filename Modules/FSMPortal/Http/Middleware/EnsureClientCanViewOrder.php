<?php

namespace Modules\FSMPortal\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\FSMCore\Models\FSMOrder;

/**
 * Ensure the authenticated client is allowed to view the requested FSM Order.
 * A client may only view orders whose location is linked to their user account
 * (fsm_locations.partner_id = auth user id).
 */
class EnsureClientCanViewOrder
{
    public function handle(Request $request, Closure $next): mixed
    {
        $id = $request->route('id');

        if ($id === null) {
            return $next($request);
        }

        $order = FSMOrder::with('location')->find((int) $id);

        if (!$order) {
            abort(404);
        }

        $userId = Auth::id();

        if (!$order->location || (int) $order->location->partner_id !== (int) $userId) {
            abort(403, 'You do not have permission to view this job.');
        }

        return $next($request);
    }
}
