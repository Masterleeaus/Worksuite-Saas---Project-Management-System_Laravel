<?php

namespace Modules\FSMCore\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMEquipment;
use Modules\FSMCore\Models\FSMLocation;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $q = FSMEquipment::query()->with('location');

        if ($request->filled('q')) {
            $term = trim((string) $request->get('q'));
            $q->where('name', 'like', "%{$term}%");
        }

        $equipment = $q->orderBy('name')->paginate(50)->withQueryString();
        $filter = $request->only(['q']);

        return view('fsmcore::equipment.index', compact('equipment', 'filter'));
    }

    public function create()
    {
        $locations = FSMLocation::where('active', true)->orderBy('name')->get();
        return view('fsmcore::equipment.create', compact('locations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:256',
            'category'        => 'nullable|string|max:128',
            'location_id'     => 'nullable|integer|exists:fsm_locations,id',
            'notes'           => 'nullable|string|max:65535',
            'warranty_expiry' => 'nullable|date',
            'active'          => 'nullable|boolean',
        ]);

        FSMEquipment::create($data);

        return redirect()->route('fsmcore.equipment.index')
            ->with('success', 'Equipment created.');
    }

    public function edit(int $id)
    {
        $item = FSMEquipment::findOrFail($id);
        $locations = FSMLocation::where('active', true)->orderBy('name')->get();
        return view('fsmcore::equipment.edit', compact('item', 'locations'));
    }

    public function update(Request $request, int $id)
    {
        $item = FSMEquipment::findOrFail($id);

        $data = $request->validate([
            'name'            => 'required|string|max:256',
            'category'        => 'nullable|string|max:128',
            'location_id'     => 'nullable|integer|exists:fsm_locations,id',
            'notes'           => 'nullable|string|max:65535',
            'warranty_expiry' => 'nullable|date',
            'active'          => 'nullable|boolean',
        ]);

        $item->update($data);

        return redirect()->route('fsmcore.equipment.index')
            ->with('success', 'Equipment updated.');
    }

    public function destroy(int $id)
    {
        $item = FSMEquipment::findOrFail($id);
        $item->delete();

        return redirect()->route('fsmcore.equipment.index')
            ->with('success', 'Equipment deleted.');
    }
}
