<?php

namespace Modules\FSMTerritory\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMTerritory\Models\FSMRegion;
use App\Models\User;

class RegionController extends Controller
{
    public function index(Request $request)
    {
        $filter  = $request->only(['q']);
        $regions = FSMRegion::with(['manager', 'districts'])
            ->when($filter['q'] ?? null, fn($q, $v) => $q->where('name', 'like', "%$v%"))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('fsmterritory::regions.index', compact('regions', 'filter'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();

        return view('fsmterritory::regions.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:256',
            'description' => 'nullable|string|max:512',
            'manager_id'  => 'nullable|integer',
        ]);

        FSMRegion::create($data);

        return redirect()->route('fsmterritory.regions.index')
            ->with('success', 'Region created successfully.');
    }

    public function edit(int $id)
    {
        $region = FSMRegion::findOrFail($id);
        $users  = User::orderBy('name')->get();

        return view('fsmterritory::regions.edit', compact('region', 'users'));
    }

    public function update(Request $request, int $id)
    {
        $region = FSMRegion::findOrFail($id);

        $data = $request->validate([
            'name'        => 'required|string|max:256',
            'description' => 'nullable|string|max:512',
            'manager_id'  => 'nullable|integer',
        ]);

        $region->update($data);

        return redirect()->route('fsmterritory.regions.index')
            ->with('success', 'Region updated successfully.');
    }

    public function destroy(int $id)
    {
        FSMRegion::findOrFail($id)->delete();

        return redirect()->route('fsmterritory.regions.index')
            ->with('success', 'Region deleted.');
    }
}
