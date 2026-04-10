<?php

namespace Modules\FSMEquipment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMEquipment\Models\RepairOrder;
use Modules\FSMEquipment\Models\RepairOrderTemplate;
use Modules\FSMEquipment\Models\EquipmentWarranty;
use Modules\FSMCore\Models\FSMEquipment;
use Modules\FSMCore\Models\FSMLocation;
use Modules\FSMCore\Models\FSMOrder;

class RepairOrderController extends Controller
{
    public function index(Request $request)
    {
        $q = RepairOrder::query()
            ->with(['equipment', 'location', 'reporter', 'assignee']);

        if ($request->filled('q')) {
            $term = trim((string) $request->get('q'));
            $q->where('name', 'like', "%{$term}%");
        }
        if ($request->filled('stage')) {
            $q->where('stage', $request->get('stage'));
        }
        if ($request->filled('priority')) {
            $q->where('priority', $request->get('priority'));
        }
        if ($request->filled('equipment_id')) {
            $q->where('equipment_id', (int) $request->get('equipment_id'));
        }

        $repairs  = $q->orderByDesc('date_reported')->paginate(50)->withQueryString();
        $filter   = $request->only(['q', 'stage', 'priority', 'equipment_id']);
        $stages   = RepairOrder::STAGES;
        $priorities = RepairOrder::PRIORITIES;
        $equipmentList = FSMEquipment::orderBy('name')->get();

        return view('fsmequipment::repair_orders.index', compact(
            'repairs', 'filter', 'stages', 'priorities', 'equipmentList'
        ));
    }

    public function show(int $id)
    {
        $repair = RepairOrder::with(['equipment', 'location', 'template', 'reporter', 'assignee', 'fsmOrder'])
            ->findOrFail($id);
        return view('fsmequipment::repair_orders.show', compact('repair'));
    }

    public function create(Request $request)
    {
        $equipmentList = FSMEquipment::orderBy('name')->get();
        $locations     = FSMLocation::where('active', true)->orderBy('name')->get();
        $templates     = RepairOrderTemplate::orderBy('name')->get();
        $users         = \App\Models\User::orderBy('name')->get();
        $selectedEquipment = $request->filled('equipment_id')
            ? FSMEquipment::find((int) $request->get('equipment_id'))
            : null;
        $selectedOrder = $request->filled('fsm_order_id')
            ? FSMOrder::find((int) $request->get('fsm_order_id'))
            : null;

        return view('fsmequipment::repair_orders.create', compact(
            'equipmentList', 'locations', 'templates', 'users',
            'selectedEquipment', 'selectedOrder'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:256',
            'equipment_id'   => 'nullable|integer|exists:fsm_equipment,id',
            'fsm_location_id'=> 'nullable|integer|exists:fsm_locations,id',
            'template_id'    => 'nullable|integer|exists:fsm_repair_order_templates,id',
            'fsm_order_id'   => 'nullable|integer|exists:fsm_orders,id',
            'description'    => 'nullable|string',
            'reported_by'    => 'nullable|integer|exists:users,id',
            'assigned_to'    => 'nullable|integer|exists:users,id',
            'priority'       => 'required|in:low,normal,urgent',
            'date_reported'  => 'nullable|date',
            'date_scheduled' => 'nullable|date',
            'date_completed' => 'nullable|date',
            'stage'          => 'required|in:new,in_progress,awaiting_parts,completed,cancelled',
            'root_cause'     => 'nullable|string',
            'cost'           => 'nullable|numeric|min:0',
            'parts_used'     => 'nullable|string',
        ]);

        // Auto-compute under_warranty
        $repair = new RepairOrder($data);
        $repair->under_warranty = $repair->computeUnderWarranty();
        $repair->save();

        return redirect()->route('fsmequipment.repair-orders.show', $repair->id)
            ->with('success', 'Repair order created.');
    }

    public function edit(int $id)
    {
        $repair        = RepairOrder::findOrFail($id);
        $equipmentList = FSMEquipment::orderBy('name')->get();
        $locations     = FSMLocation::where('active', true)->orderBy('name')->get();
        $templates     = RepairOrderTemplate::orderBy('name')->get();
        $users         = \App\Models\User::orderBy('name')->get();

        return view('fsmequipment::repair_orders.edit', compact(
            'repair', 'equipmentList', 'locations', 'templates', 'users'
        ));
    }

    public function update(Request $request, int $id)
    {
        $repair = RepairOrder::findOrFail($id);

        $data = $request->validate([
            'name'           => 'required|string|max:256',
            'equipment_id'   => 'nullable|integer|exists:fsm_equipment,id',
            'fsm_location_id'=> 'nullable|integer|exists:fsm_locations,id',
            'template_id'    => 'nullable|integer|exists:fsm_repair_order_templates,id',
            'fsm_order_id'   => 'nullable|integer|exists:fsm_orders,id',
            'description'    => 'nullable|string',
            'reported_by'    => 'nullable|integer|exists:users,id',
            'assigned_to'    => 'nullable|integer|exists:users,id',
            'priority'       => 'required|in:low,normal,urgent',
            'date_reported'  => 'nullable|date',
            'date_scheduled' => 'nullable|date',
            'date_completed' => 'nullable|date',
            'stage'          => 'required|in:new,in_progress,awaiting_parts,completed,cancelled',
            'root_cause'     => 'nullable|string',
            'cost'           => 'nullable|numeric|min:0',
            'parts_used'     => 'nullable|string',
        ]);

        $repair->fill($data);
        $repair->under_warranty = $repair->computeUnderWarranty();
        $repair->save();

        return redirect()->route('fsmequipment.repair-orders.show', $repair->id)
            ->with('success', 'Repair order updated.');
    }

    public function destroy(int $id)
    {
        $repair = RepairOrder::findOrFail($id);
        $repair->delete();

        return redirect()->route('fsmequipment.repair-orders.index')
            ->with('success', 'Repair order deleted.');
    }
}
