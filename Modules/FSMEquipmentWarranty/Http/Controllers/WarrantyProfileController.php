<?php

namespace Modules\FSMEquipmentWarranty\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMEquipmentWarranty\Models\FSMWarrantyProfile;

class WarrantyProfileController extends Controller
{
    public function index(Request $request)
    {
        $filter   = $request->only(['q']);
        $profiles = FSMWarrantyProfile::when($filter['q'] ?? null, fn($q, $v) => $q->where('name', 'like', "%$v%"))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('fsmequipmentwarranty::profiles.index', compact('profiles', 'filter'));
    }

    public function create()
    {
        return view('fsmequipmentwarranty::profiles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'               => 'required|string|max:256',
            'equipment_category' => 'nullable|string|max:256',
            'warranty_duration'  => 'required|integer|min:1',
            'warranty_type'      => 'required|in:day,week,month,year',
            'notes'              => 'nullable|string',
        ]);

        FSMWarrantyProfile::create($data);

        return redirect()->route('fsmequipmentwarranty.profiles.index')
            ->with('success', 'Warranty profile created.');
    }

    public function edit(int $id)
    {
        $profile = FSMWarrantyProfile::findOrFail($id);
        return view('fsmequipmentwarranty::profiles.edit', compact('profile'));
    }

    public function update(Request $request, int $id)
    {
        $profile = FSMWarrantyProfile::findOrFail($id);

        $data = $request->validate([
            'name'               => 'required|string|max:256',
            'equipment_category' => 'nullable|string|max:256',
            'warranty_duration'  => 'required|integer|min:1',
            'warranty_type'      => 'required|in:day,week,month,year',
            'notes'              => 'nullable|string',
        ]);

        $profile->update($data);

        return redirect()->route('fsmequipmentwarranty.profiles.index')
            ->with('success', 'Warranty profile updated.');
    }

    public function destroy(int $id)
    {
        FSMWarrantyProfile::findOrFail($id)->delete();
        return redirect()->route('fsmequipmentwarranty.profiles.index')
            ->with('success', 'Warranty profile deleted.');
    }
}
