<?php

namespace Modules\FSMRouteAvailability\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMRouteAvailability\Models\FSMBlackoutDay;
use Modules\FSMRouteAvailability\Models\FSMBlackoutGroup;

class BlackoutDayController extends Controller
{
    public function index(Request $request)
    {
        $days = FSMBlackoutDay::with('group')
            ->when($request->input('group_id'), fn ($q, $v) => $q->where('blackout_group_id', $v))
            ->orderBy('date')
            ->paginate(20)
            ->withQueryString();

        $groups = FSMBlackoutGroup::orderBy('name')->get();

        return view('fsmrouteavailability::days.index', compact('days', 'groups'));
    }

    public function create()
    {
        $groups = FSMBlackoutGroup::orderBy('name')->get();

        return view('fsmrouteavailability::days.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'blackout_group_id' => 'required|integer|exists:fsm_blackout_groups,id',
            'date'              => 'required|date',
            'label'             => 'nullable|string|max:256',
            'zip'               => 'nullable|string|max:20',
        ]);

        FSMBlackoutDay::create($data);

        return redirect()->route('fsmrouteavailability.days.index')
            ->with('success', 'Blackout day created successfully.');
    }

    public function edit(int $id)
    {
        $day    = FSMBlackoutDay::findOrFail($id);
        $groups = FSMBlackoutGroup::orderBy('name')->get();

        return view('fsmrouteavailability::days.edit', compact('day', 'groups'));
    }

    public function update(Request $request, int $id)
    {
        $day = FSMBlackoutDay::findOrFail($id);

        $data = $request->validate([
            'blackout_group_id' => 'required|integer|exists:fsm_blackout_groups,id',
            'date'              => 'required|date',
            'label'             => 'nullable|string|max:256',
            'zip'               => 'nullable|string|max:20',
        ]);

        $day->update($data);

        return redirect()->route('fsmrouteavailability.days.index')
            ->with('success', 'Blackout day updated successfully.');
    }

    public function destroy(int $id)
    {
        FSMBlackoutDay::findOrFail($id)->delete();

        return redirect()->route('fsmrouteavailability.days.index')
            ->with('success', 'Blackout day deleted.');
    }
}
