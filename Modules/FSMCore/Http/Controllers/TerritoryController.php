<?php

namespace Modules\FSMCore\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMTerritory;

class TerritoryController extends Controller
{
    public function index()
    {
        $territories = FSMTerritory::whereNull('parent_id')
            ->with('children.children.children')
            ->orderBy('name')
            ->get();
        return view('fsmcore::territories.index', compact('territories'));
    }

    public function create()
    {
        $parents = FSMTerritory::orderBy('name')->get();
        return view('fsmcore::territories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:128',
            'type'        => 'required|in:region,district,branch,territory',
            'parent_id'   => 'nullable|integer|exists:fsm_territories,id',
            'zip_codes'   => 'nullable|string',
            'description' => 'nullable|string|max:65535',
            'active'      => 'nullable|boolean',
        ]);

        FSMTerritory::create($data);

        return redirect()->route('fsmcore.territories.index')
            ->with('success', 'Territory created.');
    }

    public function edit(int $id)
    {
        $territory = FSMTerritory::findOrFail($id);
        $parents = FSMTerritory::where('id', '!=', $id)->orderBy('name')->get();
        return view('fsmcore::territories.edit', compact('territory', 'parents'));
    }

    public function update(Request $request, int $id)
    {
        $territory = FSMTerritory::findOrFail($id);

        $data = $request->validate([
            'name'        => 'required|string|max:128',
            'type'        => 'required|in:region,district,branch,territory',
            'parent_id'   => 'nullable|integer|exists:fsm_territories,id',
            'zip_codes'   => 'nullable|string',
            'description' => 'nullable|string|max:65535',
            'active'      => 'nullable|boolean',
        ]);

        $territory->update($data);

        return redirect()->route('fsmcore.territories.index')
            ->with('success', 'Territory updated.');
    }

    public function destroy(int $id)
    {
        $territory = FSMTerritory::findOrFail($id);
        $territory->delete();

        return redirect()->route('fsmcore.territories.index')
            ->with('success', 'Territory deleted.');
    }
}
