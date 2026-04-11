<?php

namespace Modules\FSMSize\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\FSMSize\Entities\FsmSize;

class FsmSizeController extends Controller
{
    public function index()
    {
        return response()->json(FsmSize::where('active', true)->orderBy('name')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:191',
            'unit_of_measure' => 'nullable|string|max:50',
            'is_order_size'   => 'boolean',
            'parent_id'       => 'nullable|integer|exists:fsm_sizes,id',
        ]);

        return response()->json(FsmSize::create($validated), 201);
    }

    public function update(Request $request, int $id)
    {
        $size = FsmSize::findOrFail($id);
        $size->update($request->only($size->getFillable()));
        return response()->json($size->fresh());
    }

    public function destroy(int $id)
    {
        FsmSize::findOrFail($id)->delete();
        return response()->json(['deleted' => true]);
    }
}
