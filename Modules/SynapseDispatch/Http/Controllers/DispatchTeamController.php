<?php

namespace Modules\SynapseDispatch\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\SynapseDispatch\Models\DispatchTeam;

class DispatchTeamController extends Controller
{
    public function index()
    {
        $teams = DispatchTeam::withCount(['workers', 'jobs'])->orderBy('name')->get();
        return view('synapsedispatch::teams.index', compact('teams'));
    }

    public function create()
    {
        return view('synapsedispatch::teams.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'          => 'required|string|max:64|unique:dispatch_teams,code',
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'planner_config' => 'nullable|array',
        ]);

        DispatchTeam::create($data);

        return redirect()->route('synapsedispatch.teams.index')
            ->with('success', 'Team created.');
    }

    public function show(DispatchTeam $team)
    {
        $team->load(['workers', 'jobs']);
        return view('synapsedispatch::teams.show', compact('team'));
    }

    public function edit(DispatchTeam $team)
    {
        return view('synapsedispatch::teams.edit', compact('team'));
    }

    public function update(Request $request, DispatchTeam $team)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'planner_config' => 'nullable|array',
        ]);

        $team->update($data);

        return redirect()->route('synapsedispatch.teams.index')
            ->with('success', "Team {$team->name} updated.");
    }

    public function destroy(DispatchTeam $team)
    {
        $name = $team->name;
        $team->delete();
        return redirect()->route('synapsedispatch.teams.index')
            ->with('success', "Team {$name} deleted.");
    }
}
