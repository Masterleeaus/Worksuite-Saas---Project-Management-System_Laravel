<?php

namespace Modules\FSMTerritory\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMTerritory\Models\FSMBranch;
use Modules\FSMTerritory\Models\FSMDistrict;
use App\Models\User;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $companyId = $this->companyId();
        $filter   = $request->only(['q']);
        $branches = FSMBranch::with(['district.region', 'manager'])
            ->where('company_id', $companyId)
            ->when($filter['q'] ?? null, fn($q, $v) => $q->where('name', 'like', "%$v%"))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('fsmterritory::branches.index', compact('branches', 'filter'));
    }

    public function create()
    {
        $companyId = $this->companyId();
        $districts = FSMDistrict::where('company_id', $companyId)->orderBy('name')->get();
        $users     = User::where('company_id', $companyId)->orderBy('name')->get();

        return view('fsmterritory::branches.create', compact('districts', 'users'));
    }

    public function store(Request $request)
    {
        $companyId = $this->companyId();
        $data = $request->validate([
            'name'        => 'required|string|max:256',
            'description' => 'nullable|string|max:512',
            'district_id' => 'nullable|integer',
            'manager_id'  => 'nullable|integer',
        ]);
        $data['company_id'] = $companyId;

        FSMBranch::create($data);

        return redirect()->route('fsmterritory.branches.index')
            ->with('success', 'Branch created successfully.');
    }

    public function edit(int $id)
    {
        $companyId = $this->companyId();
        $branch    = FSMBranch::where('company_id', $companyId)->findOrFail($id);
        $districts = FSMDistrict::where('company_id', $companyId)->orderBy('name')->get();
        $users     = User::where('company_id', $companyId)->orderBy('name')->get();

        return view('fsmterritory::branches.edit', compact('branch', 'districts', 'users'));
    }

    public function update(Request $request, int $id)
    {
        $branch = FSMBranch::where('company_id', $this->companyId())->findOrFail($id);

        $data = $request->validate([
            'name'        => 'required|string|max:256',
            'description' => 'nullable|string|max:512',
            'district_id' => 'nullable|integer',
            'manager_id'  => 'nullable|integer',
        ]);

        $branch->update($data);

        return redirect()->route('fsmterritory.branches.index')
            ->with('success', 'Branch updated successfully.');
    }

    public function destroy(int $id)
    {
        FSMBranch::where('company_id', $this->companyId())->findOrFail($id)->delete();

        return redirect()->route('fsmterritory.branches.index')
            ->with('success', 'Branch deleted.');
    }

    private function companyId(): int
    {
        $user = auth()->user();
        abort_if(!$user || !$user->company_id, 403);

        return (int)$user->company_id;
    }
}
