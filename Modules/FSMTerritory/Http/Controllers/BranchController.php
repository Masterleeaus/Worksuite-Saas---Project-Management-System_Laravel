<?php

namespace Modules\FSMTerritory\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMTerritory\Models\FSMBranch;
use Modules\FSMTerritory\Models\FSMDistrict;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->only(['q']);
        $branches = FSMBranch::with(['district', 'manager'])
            ->where('company_id', company()->id)
            ->when($filter['q'] ?? null, fn ($query, $value) => $query->where('name', 'like', "%{$value}%"))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('fsmterritory::branches.index', compact('branches', 'filter'));
    }

    public function create()
    {
        $districts = FSMDistrict::where('company_id', company()->id)->orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('fsmterritory::branches.create', compact('districts', 'users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:256',
            'description' => 'nullable|string|max:512',
            'district_id' => 'nullable|integer',
            'manager_id' => 'nullable|integer',
        ]);
        $data['company_id'] = company()->id;

        FSMBranch::create($data);

        return redirect()->route('fsmterritory.branches.index')
            ->with('success', 'Branch created successfully.');
    }

    public function edit(int $id)
    {
        $branch = FSMBranch::where('company_id', company()->id)->findOrFail($id);
        $districts = FSMDistrict::where('company_id', company()->id)->orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('fsmterritory::branches.edit', compact('branch', 'districts', 'users'));
    }

    public function update(Request $request, int $id)
    {
        $branch = FSMBranch::where('company_id', company()->id)->findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:256',
            'description' => 'nullable|string|max:512',
            'district_id' => 'nullable|integer',
            'manager_id' => 'nullable|integer',
        ]);

        $branch->update($data);

        return redirect()->route('fsmterritory.branches.index')
            ->with('success', 'Branch updated successfully.');
    }

    public function destroy(int $id)
    {
        FSMBranch::where('company_id', company()->id)->findOrFail($id)->delete();

        return redirect()->route('fsmterritory.branches.index')
            ->with('success', 'Branch deleted.');
    }
}
