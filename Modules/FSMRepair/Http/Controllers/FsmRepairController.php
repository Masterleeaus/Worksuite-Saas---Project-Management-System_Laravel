<?php

namespace Modules\FSMRepair\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\FSMRepair\Entities\FsmRepairOrder;

class FsmRepairController extends Controller
{
    public function index()
    {
        return response()->json(FsmRepairOrder::with('fsmOrder')->latest()->paginate(25));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fsm_order_id'       => 'nullable|integer',
            'product_id'         => 'nullable|integer',
            'problem_description'=> 'nullable|string',
            'technician_id'      => 'nullable|integer',
            'scheduled_date'     => 'nullable|date',
        ]);

        $validated['name']       = 'RPR-' . strtoupper(uniqid());
        $validated['created_by'] = auth()->id();

        return response()->json(FsmRepairOrder::create($validated), 201);
    }

    public function show(int $id)
    {
        return response()->json(FsmRepairOrder::with('fsmOrder')->findOrFail($id));
    }

    public function update(Request $request, int $id)
    {
        $order = FsmRepairOrder::findOrFail($id);
        $order->update($request->only($order->getFillable()));
        return response()->json($order->fresh());
    }

    public function destroy(int $id)
    {
        FsmRepairOrder::findOrFail($id)->delete();
        return response()->json(['deleted' => true]);
    }
}
