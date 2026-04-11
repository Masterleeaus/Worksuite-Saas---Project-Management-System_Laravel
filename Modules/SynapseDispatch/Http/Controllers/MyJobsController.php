<?php

namespace Modules\SynapseDispatch\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\SynapseDispatch\Models\DispatchWorker;
use Modules\SynapseDispatch\Models\DispatchJob;
use Modules\SynapseDispatch\Enums\PlanningStatus;

class MyJobsController extends Controller
{
    /**
     * Show the jobs assigned to the currently logged-in Worksuite user's DispatchWorker record.
     */
    public function index()
    {
        $userId = Auth::id();

        $worker = DispatchWorker::where('worksuite_user_id', $userId)->first();

        if (!$worker) {
            return view('synapsedispatch::my_jobs.not_linked');
        }

        $jobs = DispatchJob::where('scheduled_primary_worker_id', $worker->id)
            ->whereIn('planning_status', [
                PlanningStatus::DISPATCHED->value,
                PlanningStatus::PLANNED->value,
            ])
            ->with(['location', 'team'])
            ->orderBy('scheduled_start_datetime')
            ->get();

        return view('synapsedispatch::my_jobs.index', compact('worker', 'jobs'));
    }
}
