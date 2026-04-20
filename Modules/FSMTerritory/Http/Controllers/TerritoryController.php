<?php

namespace Modules\FSMTerritory\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMTerritory\Models\FSMTerritory;
use Modules\FSMTerritory\Models\FSMBranch;

class TerritoryController extends Controller
{
    public function index(Request $request)
    {
        $companyId = $this->companyId();
        $filter      = $request->only(['q']);
        $territories = FSMTerritory::with(['branch.district.region'])
            ->where('company_id', $companyId)
            ->when($filter['q'] ?? null, fn($q, $v) => $q->where('name', 'like', "%$v%"))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('fsmterritory::territories.index', compact('territories', 'filter'));
    }

    public function create()
    {
        $branches = FSMBranch::where('company_id', $this->companyId())->orderBy('name')->get();
        $types    = FSMTerritory::TYPES;

        return view('fsmterritory::territories.create', compact('branches', 'types'));
    }

    public function store(Request $request)
    {
        $companyId = $this->companyId();
        $data = $request->validate([
            'name'        => 'required|string|max:256',
            'description' => 'nullable|string|max:512',
            'branch_id'   => 'nullable|integer',
            'type'        => 'nullable|string|max:32',
            'zip_codes'   => 'nullable|string',
        ]);
        $data['company_id'] = $companyId;

        FSMTerritory::create($data);

        return redirect()->route('fsmterritory.territories.index')
            ->with('success', 'Territory created successfully.');
    }

    public function edit(int $id)
    {
        $companyId = $this->companyId();
        $territory = FSMTerritory::where('company_id', $companyId)->findOrFail($id);
        $branches  = FSMBranch::where('company_id', $companyId)->orderBy('name')->get();
        $types     = FSMTerritory::TYPES;

        return view('fsmterritory::territories.edit', compact('territory', 'branches', 'types'));
    }

    public function update(Request $request, int $id)
    {
        $territory = FSMTerritory::where('company_id', $this->companyId())->findOrFail($id);

        $data = $request->validate([
            'name'        => 'required|string|max:256',
            'description' => 'nullable|string|max:512',
            'branch_id'   => 'nullable|integer',
            'type'        => 'nullable|string|max:32',
            'zip_codes'   => 'nullable|string',
        ]);

        $territory->update($data);

        return redirect()->route('fsmterritory.territories.index')
            ->with('success', 'Territory updated successfully.');
    }

    public function destroy(int $id)
    {
        FSMTerritory::where('company_id', $this->companyId())->findOrFail($id)->delete();

        return redirect()->route('fsmterritory.territories.index')
            ->with('success', 'Territory deleted.');
    }

    private function companyId(): int
    {
        $user = auth()->user();
        abort_if(!$user || !$user->company_id, 403);

        return (int)$user->company_id;
    }
}
