<?php

namespace Modules\FSMTerritory\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMTerritory\Models\FSMBranch;
use Modules\FSMTerritory\Models\FSMTerritory;

class TerritoryController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->only(['q']);
        $territories = FSMTerritory::with(['branch'])
            ->where('company_id', company()->id)
            ->when($filter['q'] ?? null, fn ($query, $value) => $query->where('name', 'like', "%{$value}%"))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('fsmterritory::territories.index', compact('territories', 'filter'));
    }

    public function create()
    {
        $branches = FSMBranch::where('company_id', company()->id)->orderBy('name')->get();

        return view('fsmterritory::territories.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:256',
            'description' => 'nullable|string|max:512',
            'branch_id' => 'nullable|integer',
            'type' => 'nullable|string|max:32',
            'zip_codes' => 'nullable|string',
        ]);
        $data['company_id'] = company()->id;

        FSMTerritory::create($data);

        return redirect()->route('fsmterritory.territories.index')
            ->with('success', 'Territory created successfully.');
    }

    public function edit(int $id)
    {
        $territory = FSMTerritory::where('company_id', company()->id)->findOrFail($id);
        $branches = FSMBranch::where('company_id', company()->id)->orderBy('name')->get();

        return view('fsmterritory::territories.edit', compact('territory', 'branches'));
    }

    public function update(Request $request, int $id)
    {
        $territory = FSMTerritory::where('company_id', company()->id)->findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:256',
            'description' => 'nullable|string|max:512',
            'branch_id' => 'nullable|integer',
            'type' => 'nullable|string|max:32',
            'zip_codes' => 'nullable|string',
        ]);

        $territory->update($data);

        return redirect()->route('fsmterritory.territories.index')
            ->with('success', 'Territory updated successfully.');
    }

    public function destroy(int $id)
    {
        FSMTerritory::where('company_id', company()->id)->findOrFail($id)->delete();

        return redirect()->route('fsmterritory.territories.index')
            ->with('success', 'Territory deleted.');
    }
}
