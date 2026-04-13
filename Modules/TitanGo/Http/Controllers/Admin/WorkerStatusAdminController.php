<?php

namespace Modules\TitanGo\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TitanGo\Models\TitanGoWorkerStatus;

/**
 * WorkerStatusAdminController
 *
 * Lists quick-action status signals sent by field workers during visits.
 *
 * Routes:
 *   GET  /account/titan-go/statuses   — index
 */
class WorkerStatusAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = TitanGoWorkerStatus::query()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('worker_id')) {
            $query->where('worker_id', $request->worker_id);
        }
        if ($request->filled('visit_id')) {
            $query->where('visit_id', $request->visit_id);
        }

        $statuses      = $query->paginate(100)->appends($request->query());
        $signalTypes   = TitanGoWorkerStatus::distinct()->pluck('status')->sort()->values();
        $filter        = $request->only(['status', 'worker_id', 'visit_id']);

        return view('titango::admin.statuses.index', compact('statuses', 'signalTypes', 'filter'));
    }
}
