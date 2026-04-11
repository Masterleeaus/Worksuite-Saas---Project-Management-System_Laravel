<?php

namespace Modules\SynapseDispatch\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\SynapseDispatch\Models\DispatchWorker;
use Modules\SynapseDispatch\Models\DispatchTeam;
use Modules\SynapseDispatch\Models\DispatchLocation;

class DispatchWorkerController extends Controller
{
    public function index(Request $request)
    {
        $q = DispatchWorker::query()->with(['team', 'location']);

        if ($request->filled('team_id')) {
            $q->where('team_id', (int) $request->get('team_id'));
        }
        if ($request->filled('q')) {
            $term = trim((string) $request->get('q'));
            $q->where(function ($sub) use ($term) {
                $sub->where('name', 'like', "%{$term}%")
                    ->orWhere('code', 'like', "%{$term}%");
            });
        }

        $workers = $q->orderBy('name')->paginate(50)->withQueryString();
        $teams   = DispatchTeam::orderBy('name')->get();
        $filter  = $request->only(['team_id', 'q']);

        return view('synapsedispatch::workers.index', compact('workers', 'teams', 'filter'));
    }

    public function create()
    {
        $teams     = DispatchTeam::orderBy('name')->get();
        $locations = DispatchLocation::orderBy('location_code')->get();
        return view('synapsedispatch::workers.create', compact('teams', 'locations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'              => 'required|string|max:64|unique:dispatch_workers,code',
            'name'              => 'required|string|max:255',
            'is_active'         => 'boolean',
            'team_id'           => 'nullable|integer|exists:dispatch_teams,id',
            'location_id'       => 'nullable|integer|exists:dispatch_locations,id',
            'business_hour'     => 'nullable|array',
            'flex_form_data'    => 'nullable|array',
            'worksuite_user_id' => 'nullable|integer|exists:users,id',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['skills']    = $this->parseSkills($request->input('skills_raw', ''));
        DispatchWorker::create($data);

        return redirect()->route('synapsedispatch.workers.index')
            ->with('success', 'Worker created.');
    }

    public function show(DispatchWorker $worker)
    {
        $worker->load(['team', 'location', 'scheduledJobs']);
        return view('synapsedispatch::workers.show', compact('worker'));
    }

    public function edit(DispatchWorker $worker)
    {
        $teams     = DispatchTeam::orderBy('name')->get();
        $locations = DispatchLocation::orderBy('location_code')->get();
        return view('synapsedispatch::workers.edit', compact('worker', 'teams', 'locations'));
    }

    public function update(Request $request, DispatchWorker $worker)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'is_active'         => 'boolean',
            'team_id'           => 'nullable|integer|exists:dispatch_teams,id',
            'location_id'       => 'nullable|integer|exists:dispatch_locations,id',
            'business_hour'     => 'nullable|array',
            'flex_form_data'    => 'nullable|array',
            'worksuite_user_id' => 'nullable|integer|exists:users,id',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['skills']    = $this->parseSkills($request->input('skills_raw', ''));
        $worker->update($data);

        return redirect()->route('synapsedispatch.workers.index')
            ->with('success', "Worker {$worker->name} updated.");
    }

    private function parseSkills(string $raw): array
    {
        return array_values(array_filter(
            array_map('trim', explode(',', $raw))
        ));
    }

    public function destroy(DispatchWorker $worker)
    {
        $name = $worker->name;
        $worker->delete();
        return redirect()->route('synapsedispatch.workers.index')
            ->with('success', "Worker {$name} deleted.");
    }

    /** GET /synapse-dispatch/workers/fc-resources — FullCalendar resource rows */
    public function fcResources(Request $request)
    {
        $q = DispatchWorker::where('is_active', true)->orderBy('name');

        if ($request->filled('team_id')) {
            $q->where('team_id', (int) $request->get('team_id'));
        }

        $resources = $q->get()->map(fn(DispatchWorker $w) => [
            'id'    => $w->id,
            'title' => $w->name,
        ]);

        return response()->json($resources);
    }
}
