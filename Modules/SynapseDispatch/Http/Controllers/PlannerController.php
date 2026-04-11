<?php

namespace Modules\SynapseDispatch\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\SynapseDispatch\Models\DispatchJob;
use Modules\SynapseDispatch\Models\DispatchTeam;
use Modules\SynapseDispatch\Models\DispatchWorker;
use Modules\SynapseDispatch\Enums\PlanningStatus;
use Modules\SynapseDispatch\Services\HeuristicPlannerService;

class PlannerController extends Controller
{
    public function __construct(protected HeuristicPlannerService $planner) {}

    public function index()
    {
        $teams         = DispatchTeam::orderBy('name')->get();
        $unplannedJobs = DispatchJob::where('planning_status', PlanningStatus::UNPLANNED->value)
            ->with(['team', 'location'])
            ->orderBy('requested_start_datetime')
            ->get();

        $dispatchedCount = DispatchJob::where('planning_status', PlanningStatus::DISPATCHED->value)->count();
        $unplannedCount  = DispatchJob::where('planning_status', PlanningStatus::UNPLANNED->value)->count();
        $workerCount     = DispatchWorker::where('is_active', true)->count();

        return view('synapsedispatch::dashboard', compact(
            'teams', 'unplannedJobs', 'dispatchedCount', 'unplannedCount', 'workerCount'
        ));
    }

    public function gantt()
    {
        $teams = DispatchTeam::orderBy('name')->get();
        return view('synapsedispatch::planner.gantt', compact('teams'));
    }

    /** GET /synapse-dispatch/planner/suggest/{job} — return top-5 ranked workers */
    public function suggest(DispatchJob $job)
    {
        $suggestions = $this->planner->suggest($job, 5);

        return response()->json($suggestions->map(fn($item) => [
            'worker_id'   => $item['worker']->id,
            'worker_code' => $item['worker']->code,
            'worker_name' => $item['worker']->name,
            'score'       => $item['score'],
            'distance_km' => $item['distance_km'],
        ]));
    }
}
