<?php

namespace Modules\FSMEquipment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMEquipment\Models\EquipmentWarranty;
use Modules\FSMCore\Models\FSMEquipment;

class EquipmentWarrantyController extends Controller
{
    public function index(Request $request, int $equipmentId)
    {
        $equipment = FSMEquipment::findOrFail($equipmentId);
        $warranties = EquipmentWarranty::where('equipment_id', $equipmentId)
            ->orderByDesc('warranty_start')
            ->paginate(20);

        return view('fsmequipment::warranties.index', compact('equipment', 'warranties'));
    }

    public function create(int $equipmentId)
    {
        $equipment = FSMEquipment::findOrFail($equipmentId);
        return view('fsmequipment::warranties.create', compact('equipment'));
    }

    public function store(Request $request, int $equipmentId)
    {
        FSMEquipment::findOrFail($equipmentId);

        $data = $request->validate([
            'warranty_start'  => 'required|date',
            'warranty_end'    => 'required|date|after_or_equal:warranty_start',
            'supplier'        => 'nullable|string|max:256',
            'warranty_number' => 'nullable|string|max:128',
            'notes'           => 'nullable|string',
        ]);

        $data['equipment_id'] = $equipmentId;
        EquipmentWarranty::create($data);

        return redirect()->route('fsmequipment.warranties.index', $equipmentId)
            ->with('success', 'Warranty record created.');
    }

    public function edit(int $equipmentId, int $id)
    {
        $equipment = FSMEquipment::findOrFail($equipmentId);
        $warranty  = EquipmentWarranty::where('equipment_id', $equipmentId)->findOrFail($id);
        return view('fsmequipment::warranties.edit', compact('equipment', 'warranty'));
    }

    public function update(Request $request, int $equipmentId, int $id)
    {
        $warranty = EquipmentWarranty::where('equipment_id', $equipmentId)->findOrFail($id);

        $data = $request->validate([
            'warranty_start'  => 'required|date',
            'warranty_end'    => 'required|date|after_or_equal:warranty_start',
            'supplier'        => 'nullable|string|max:256',
            'warranty_number' => 'nullable|string|max:128',
            'notes'           => 'nullable|string',
        ]);

        $warranty->update($data);

        return redirect()->route('fsmequipment.warranties.index', $equipmentId)
            ->with('success', 'Warranty record updated.');
    }

    public function destroy(int $equipmentId, int $id)
    {
        $warranty = EquipmentWarranty::where('equipment_id', $equipmentId)->findOrFail($id);
        $warranty->delete();

        return redirect()->route('fsmequipment.warranties.index', $equipmentId)
            ->with('success', 'Warranty record deleted.');
    }
}
