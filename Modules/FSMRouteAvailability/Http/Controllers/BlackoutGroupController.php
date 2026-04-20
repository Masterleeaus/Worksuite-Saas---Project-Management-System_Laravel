<?php

namespace Modules\FSMRouteAvailability\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMRouteAvailability\Models\FSMBlackoutGroup;

class BlackoutGroupController extends Controller
{
    public function index(Request $request)
    {
        $groups = FSMBlackoutGroup::withCount('blackoutDays')
            ->when($request->input('q'), fn ($q, $v) => $q->where('name', 'like', "%$v%"))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('fsmrouteavailability::groups.index', compact('groups'));
    }

    public function create()
    {
        return view('fsmrouteavailability::groups.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:256',
            'description' => 'nullable|string|max:512',
        ]);

        FSMBlackoutGroup::create($data);

        return redirect()->route('fsmrouteavailability.groups.index')
            ->with('success', 'Blackout group created successfully.');
    }

    public function edit(int $id)
    {
        $group = FSMBlackoutGroup::with('blackoutDays')->findOrFail($id);

        return view('fsmrouteavailability::groups.edit', compact('group'));
    }

    public function update(Request $request, int $id)
    {
        $group = FSMBlackoutGroup::findOrFail($id);

        $data = $request->validate([
            'name'        => 'required|string|max:256',
            'description' => 'nullable|string|max:512',
        ]);

        $group->update($data);

        return redirect()->route('fsmrouteavailability.groups.index')
            ->with('success', 'Blackout group updated successfully.');
    }

    public function destroy(int $id)
    {
        FSMBlackoutGroup::findOrFail($id)->delete();

        return redirect()->route('fsmrouteavailability.groups.index')
            ->with('success', 'Blackout group deleted.');
    }
}
