<?php

namespace Modules\FSMActivity\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMActivity\Models\FSMActivityType;

class ActivityTypeController extends Controller
{
    public function index()
    {
        $types = FSMActivityType::withCount('activities')->orderBy('name')->paginate(50);
        return view('fsmactivity::activity_types.index', compact('types'));
    }

    public function create()
    {
        $users = \App\Models\User::orderBy('name')->get(['id', 'name']);
        return view('fsmactivity::activity_types.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:128',
            'icon'            => 'nullable|string|max:64',
            'delay_count'     => 'nullable|integer|min:1',
            'delay_unit'      => 'nullable|string|in:days,weeks,months',
            'default_user_id' => 'nullable|integer',
            'summary'         => 'nullable|string|max:255',
            'active'          => 'nullable|boolean',
        ]);
        $data['active'] = $request->boolean('active', true);

        FSMActivityType::create($data);

        return redirect()->route('fsmactivity.types.index')
            ->with('success', 'Activity type created.');
    }

    public function edit(int $id)
    {
        $type  = FSMActivityType::findOrFail($id);
        $users = \App\Models\User::orderBy('name')->get(['id', 'name']);
        return view('fsmactivity::activity_types.edit', compact('type', 'users'));
    }

    public function update(Request $request, int $id)
    {
        $type = FSMActivityType::findOrFail($id);
        $data = $request->validate([
            'name'            => 'required|string|max:128',
            'icon'            => 'nullable|string|max:64',
            'delay_count'     => 'nullable|integer|min:1',
            'delay_unit'      => 'nullable|string|in:days,weeks,months',
            'default_user_id' => 'nullable|integer',
            'summary'         => 'nullable|string|max:255',
            'active'          => 'nullable|boolean',
        ]);
        $data['active'] = $request->boolean('active', true);

        $type->update($data);

        return redirect()->route('fsmactivity.types.index')
            ->with('success', 'Activity type updated.');
    }

    public function destroy(int $id)
    {
        FSMActivityType::findOrFail($id)->delete();
        return redirect()->route('fsmactivity.types.index')
            ->with('success', 'Activity type deleted.');
    }
}
