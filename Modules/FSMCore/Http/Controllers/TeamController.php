<?php

namespace Modules\FSMCore\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMTeam;

class TeamController extends Controller
{
    public function index()
    {
        $teams = FSMTeam::withCount('members')->orderBy('name')->paginate(50);
        return view('fsmcore::teams.index', compact('teams'));
    }

    public function create()
    {
        return view('fsmcore::teams.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:128',
            'description' => 'nullable|string|max:65535',
            'active'      => 'nullable|boolean',
            'member_ids'  => 'nullable|array',
            'member_ids.*'=> 'integer',
        ]);

        $team = FSMTeam::create($data);

        if (!empty($data['member_ids'])) {
            $team->members()->sync($data['member_ids']);
        }

        return redirect()->route('fsmcore.teams.show', $team->id)
            ->with('success', 'Team created.');
    }

    public function show(int $id)
    {
        $team = FSMTeam::with('members')->findOrFail($id);
        return view('fsmcore::teams.show', compact('team'));
    }

    public function edit(int $id)
    {
        $team = FSMTeam::with('members')->findOrFail($id);
        return view('fsmcore::teams.edit', compact('team'));
    }

    public function update(Request $request, int $id)
    {
        $team = FSMTeam::findOrFail($id);

        $data = $request->validate([
            'name'        => 'required|string|max:128',
            'description' => 'nullable|string|max:65535',
            'active'      => 'nullable|boolean',
            'member_ids'  => 'nullable|array',
            'member_ids.*'=> 'integer',
        ]);

        $team->update($data);
        $team->members()->sync($data['member_ids'] ?? []);

        return redirect()->route('fsmcore.teams.show', $team->id)
            ->with('success', 'Team updated.');
    }

    public function destroy(int $id)
    {
        $team = FSMTeam::findOrFail($id);
        $team->delete();

        return redirect()->route('fsmcore.teams.index')
            ->with('success', 'Team deleted.');
    }
}
