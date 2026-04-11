<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Inventory\Models\Warehouse;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::query()->latest()->paginate(20);
        return view('inventory::warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        return view('inventory::warehouses.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:190',
            'code'      => 'nullable|string|max:50|unique:warehouses,code',
            'address'   => 'nullable|string|max:500',
            'type'      => 'nullable|in:depot,van,office',
            'is_active' => 'sometimes|boolean',
        ]);
        $data['company_id'] = company()->id ?? null;
        $data['is_active']  = $request->boolean('is_active', true);
        Warehouse::create($data);
        return redirect()->route('inventory.warehouses.index')->with('status', 'Warehouse created');
    }

    public function edit(Warehouse $warehouse)
    {
        return view('inventory::warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:190',
            'code'      => 'nullable|string|max:50|unique:warehouses,code,' . $warehouse->id,
            'address'   => 'nullable|string|max:500',
            'type'      => 'nullable|in:depot,van,office',
            'is_active' => 'sometimes|boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        $warehouse->update($data);
        return redirect()->route('inventory.warehouses.index')->with('status', 'Warehouse updated');
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();
        return back()->with('status', 'Warehouse deleted');
    }
}
