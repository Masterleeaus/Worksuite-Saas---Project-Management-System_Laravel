<?php

namespace Modules\SynapseDispatch\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\SynapseDispatch\Models\DispatchJob;
use Modules\SynapseDispatch\Models\DispatchWorker;
use Modules\SynapseDispatch\Models\DispatchTeam;
use Modules\SynapseDispatch\Models\DispatchLocation;
use Modules\SynapseDispatch\Enums\PlanningStatus;
use Modules\SynapseDispatch\Enums\LifeCycleStatus;
use Modules\SynapseDispatch\Services\JobAssignmentService;
use Modules\SynapseDispatch\Jobs\DispatchJobAssignment;

class DispatchJobController extends Controller
{
    public function index(Request $request)
    {
        $q = DispatchJob::query()->with(['team', 'location', 'scheduledPrimaryWorker']);

        if ($request->filled('team_id')) {
            $q->where('team_id', (int) $request->get('team_id'));
        }
        if ($request->filled('planning_status')) {
            $q->where('planning_status', $request->string('planning_status')->toString());
        }
        if ($request->filled('q')) {
            $term = trim((string) $request->get('q'));
            $q->where(function ($sub) use ($term) {
                $sub->where('code', 'like', "%{$term}%")
                    ->orWhere('name', 'like', "%{$term}%");
            });
        }

        $jobs    = $q->orderByDesc('id')->paginate(50)->withQueryString();
        $teams   = DispatchTeam::orderBy('name')->get();
        $filter  = $request->only(['team_id', 'planning_status', 'q']);

        return view('synapsedispatch::jobs.index', compact('jobs', 'teams', 'filter'));
    }

    public function create()
    {
        $teams     = DispatchTeam::orderBy('name')->get();
        $workers   = DispatchWorker::where('is_active', true)->orderBy('name')->get();
        $locations = DispatchLocation::orderBy('location_code')->get();

        return view('synapsedispatch::jobs.create', compact('teams', 'workers', 'locations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'job_type'                   => 'required|in:JOB,ABSENCE',
            'name'                       => 'nullable|string|max:255',
            'description'                => 'nullable|string',
            'team_id'                    => 'nullable|integer|exists:dispatch_teams,id',
            'location_id'                => 'nullable|integer|exists:dispatch_locations,id',
            'requested_start_datetime'   => 'nullable|date',
            'requested_duration_minutes' => 'required|numeric|min:1',
            'auto_planning'              => 'boolean',
            'flex_form_data'             => 'nullable|array',
        ]);

        $data['planning_status']    = PlanningStatus::UNPLANNED;
        $data['life_cycle_status']  = LifeCycleStatus::CREATED;
        $data['auto_planning']      = $request->boolean('auto_planning', true);

        $job = DispatchJob::create($data);

        if ($job->auto_planning) {
            DispatchJobAssignment::dispatch($job)->onQueue(config('synapsedispatch.queue', 'dispatch'));
        }

        return redirect()->route('synapsedispatch.jobs.index')
            ->with('success', "Job {$job->code} created.");
    }

    public function show(DispatchJob $job)
    {
        $job->load(['team', 'location', 'scheduledPrimaryWorker', 'secondaryWorkers', 'events']);
        return view('synapsedispatch::jobs.show', compact('job'));
    }

    public function edit(DispatchJob $job)
    {
        $teams     = DispatchTeam::orderBy('name')->get();
        $workers   = DispatchWorker::where('is_active', true)->orderBy('name')->get();
        $locations = DispatchLocation::orderBy('location_code')->get();
        return view('synapsedispatch::jobs.edit', compact('job', 'teams', 'workers', 'locations'));
    }

    public function update(Request $request, DispatchJob $job)
    {
        $data = $request->validate([
            'name'                       => 'nullable|string|max:255',
            'description'                => 'nullable|string',
            'team_id'                    => 'nullable|integer|exists:dispatch_teams,id',
            'location_id'                => 'nullable|integer|exists:dispatch_locations,id',
            'requested_start_datetime'   => 'nullable|date',
            'requested_duration_minutes' => 'required|numeric|min:1',
            'auto_planning'              => 'boolean',
            'flex_form_data'             => 'nullable|array',
        ]);

        $data['auto_planning'] = $request->boolean('auto_planning', true);
        $job->update($data);

        return redirect()->route('synapsedispatch.jobs.index')
            ->with('success', "Job {$job->code} updated.");
    }

    public function destroy(DispatchJob $job)
    {
        $code = $job->code;
        $job->delete();
        return redirect()->route('synapsedispatch.jobs.index')
            ->with('success', "Job {$code} deleted.");
    }

    /** PATCH /synapse-dispatch/jobs/{job}/reschedule */
    public function reschedule(Request $request, DispatchJob $job, JobAssignmentService $assigner)
    {
        $data = $request->validate([
            'worker_id' => 'required|integer|exists:dispatch_workers,id',
            'start'     => 'required|date',
        ]);

        $worker = DispatchWorker::findOrFail($data['worker_id']);
        $start  = \Carbon\Carbon::parse($data['start']);

        $assigner->reschedule($job, $worker, $start);

        return response()->json(['status' => 'ok', 'job_code' => $job->code]);
    }

    /** PATCH /synapse-dispatch/jobs/{job}/assign */
    public function manualAssign(Request $request, DispatchJob $job, JobAssignmentService $assigner)
    {
        $data = $request->validate([
            'worker_id' => 'required|integer|exists:dispatch_workers,id',
        ]);

        $worker = DispatchWorker::findOrFail($data['worker_id']);
        $assigner->assign($job, $worker, 'MANUAL');

        return redirect()->back()->with('success', "Job {$job->code} assigned to {$worker->name}.");
    }

    /** POST /synapse-dispatch/jobs/{job}/dispatch */
    public function triggerAutoDispatch(DispatchJob $job)
    {
        DispatchJobAssignment::dispatch($job)->onQueue(config('synapsedispatch.queue', 'dispatch'));
        return redirect()->back()->with('success', "Auto-dispatch queued for job {$job->code}.");
    }

    /** GET /synapse-dispatch/jobs/fc-events */
    public function fcEvents(Request $request)
    {
        $q = DispatchJob::query()
            ->with(['scheduledPrimaryWorker'])
            ->whereNotNull('scheduled_start_datetime')
            ->whereNotNull('scheduled_duration_minutes');

        if ($request->filled('team_id')) {
            $q->where('team_id', (int) $request->get('team_id'));
        }
        if ($request->filled('start')) {
            $q->where('scheduled_start_datetime', '>=', $request->get('start'));
        }
        if ($request->filled('end')) {
            $q->whereRaw(
                'DATE_ADD(scheduled_start_datetime, INTERVAL scheduled_duration_minutes MINUTE) <= ?',
                [$request->get('end')]
            );
        }

        $events = $q->get()->map(function (DispatchJob $job) {
            $end = $job->scheduled_start_datetime
                ->copy()
                ->addMinutes((int) $job->scheduled_duration_minutes);

            $colorMap = [
                PlanningStatus::UNPLANNED->value  => '#6c757d',
                PlanningStatus::PLANNED->value    => '#0dcaf0',
                PlanningStatus::DISPATCHED->value => '#0d6efd',
                PlanningStatus::DECLINED->value   => '#dc3545',
                PlanningStatus::COMPLETED->value  => '#198754',
            ];

            return [
                'id'         => $job->id,
                'resourceId' => $job->scheduled_primary_worker_id,
                'title'      => $job->code . ($job->name ? " – {$job->name}" : ''),
                'start'      => $job->scheduled_start_datetime->toIso8601String(),
                'end'        => $end->toIso8601String(),
                'color'      => $colorMap[$job->planning_status->value] ?? '#6c757d',
            ];
        });

        return response()->json($events);
    }
}
