<?php

namespace Modules\FSMStock\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMEquipment;
use Modules\FSMCore\Models\FSMLocation;
use Modules\FSMStock\Models\FSMLocationEquipmentRegister;

class LocationEquipmentController extends Controller
{
    public function index($locationId)
    {
        $location  = FSMLocation::findOrFail($locationId);
        $registers = FSMLocationEquipmentRegister::with(['equipment', 'checkEvents'])
            ->where('location_id', $locationId)
            ->get();
        $equipment = FSMEquipment::where('active', true)->orderBy('name')->get();

        return view('fsmstock::location_equipment.index', compact('location', 'registers', 'equipment'));
    }

    public function store(Request $request, $locationId)
    {
        FSMLocation::findOrFail($locationId);

        $data = $request->validate([
            'fsm_equipment_id' => 'required|integer|exists:fsm_equipment,id',
            'notes'            => 'nullable|string',
        ]);

        FSMLocationEquipmentRegister::create(array_merge($data, [
            'location_id' => $locationId,
        ]));

        return redirect()->back()->with('success', 'Equipment added to register.');
    }

    public function destroy($id)
    {
        FSMLocationEquipmentRegister::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Equipment removed from register.');
    }
}
