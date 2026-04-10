<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Inventory\Models\Movement;
use Modules\Inventory\Models\Item;
use Modules\Inventory\Models\Warehouse;

class MovementController extends Controller
{
    public function index()
    {
        $movements = Movement::with(['item','warehouse'])->latest()->paginate(20);
        return view('inventory::movements.index', compact('movements'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'item_id' => 'required|exists:inventory_items,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|numeric',
            'type' => 'required|in:in,out,adjust',
            'note' => 'nullable|string',
        ]);
        Movement::create($data);
        return back()->with('status','Movement recorded');
    }

    public function destroy(Movement $movement)
    {
        $movement->delete();
        return back()->with('status','Movement deleted');
    }
}
