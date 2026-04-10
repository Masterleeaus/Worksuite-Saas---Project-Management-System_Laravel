<?php

namespace Modules\FSMStock\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMStock\Models\FSMEquipmentCheckEvent;
use Modules\FSMStock\Models\FSMLocationEquipmentRegister;

class EquipmentCheckController extends Controller
{
    public function store(Request $request, $registerId)
    {
        FSMLocationEquipmentRegister::findOrFail($registerId);

        $data = $request->validate([
            'event_type'   => 'required|in:check_in,check_out',
            'notes'        => 'nullable|string',
            'fsm_order_id' => 'nullable|integer|exists:fsm_orders,id',
        ]);

        FSMEquipmentCheckEvent::create(array_merge($data, [
            'register_id' => $registerId,
            'checked_by'  => auth()->id(),
            'checked_at'  => now(),
        ]));

        return redirect()->back()->with('success', 'Equipment check recorded.');
    }
}
