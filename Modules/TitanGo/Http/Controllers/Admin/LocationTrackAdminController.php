<?php

namespace Modules\TitanGo\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TitanGo\Models\TitanGoLocationPing;

/**
 * LocationTrackAdminController
 *
 * Shows GPS heartbeat pings from field workers.
 * Provides a tabular view with per-worker filtering.
 *
 * Routes:
 *   GET  /account/titan-go/tracking   — index
 */
class LocationTrackAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = TitanGoLocationPing::query()->latest();

        if ($request->filled('worker_id')) {
            $query->where('worker_id', $request->worker_id);
        }
        if ($request->filled('visit_id')) {
            $query->where('visit_id', $request->visit_id);
        }
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $pings  = $query->paginate(100)->appends($request->query());
        $filter = $request->only(['worker_id', 'visit_id', 'date']);

        return view('titango::admin.tracking.index', compact('pings', 'filter'));
    }
}
