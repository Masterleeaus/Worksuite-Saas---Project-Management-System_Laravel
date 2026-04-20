<?php

namespace Modules\FSMTerritory\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMTerritory\Models\FSMDistrict;
use Modules\FSMTerritory\Models\FSMRegion;
use App\Models\User;

class DistrictController extends Controller
{
    public function index(Request $request)
    {
        $filter    = $request->only(['q']);
        $districts = FSMDistrict::with(['region', 'manager'])
            ->when($filter['q'] ?? null, fn($q, $v) => $q->where('name', 'like', "%$v%"))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('fsmterritory::districts.index', compact('districts', 'filter'));
    }

    public function create()
    {
        $regions = FSMRegion::orderBy('name')->get();
        $users   = User::orderBy('name')->get();

        return view('fsmterritory::districts.create', compact('regions', 'users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:256',
            'description' => 'nullable|string|max:512',
            'region_id'   => 'nullable|integer',
            'manager_id'  => 'nullable|integer',
        ]);

        FSMDistrict::create($data);

        return redirect()->route('fsmterritory.districts.index')
            ->with('success', 'District created successfully.');
    }

    public function edit(int $id)
    {
        $district = FSMDistrict::findOrFail($id);
        $regions  = FSMRegion::orderBy('name')->get();
        $users    = User::orderBy('name')->get();

        return view('fsmterritory::districts.edit', compact('district', 'regions', 'users'));
    }

    public function update(Request $request, int $id)
    {
        $district = FSMDistrict::findOrFail($id);

        $data = $request->validate([
            'name'        => 'required|string|max:256',
            'description' => 'nullable|string|max:512',
            'region_id'   => 'nullable|integer',
            'manager_id'  => 'nullable|integer',
        ]);

        $district->update($data);

        return redirect()->route('fsmterritory.districts.index')
            ->with('success', 'District updated successfully.');
    }

    public function destroy(int $id)
    {
        FSMDistrict::findOrFail($id)->delete();

        return redirect()->route('fsmterritory.districts.index')
            ->with('success', 'District deleted.');
    }
}
