<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Inventory\Models\Item;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::query()->latest()->paginate(20);
        return view('inventory::items.index', compact('items'));
    }

    public function create()
    {
        return view('inventory::items.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:190',
            'sku' => 'nullable|string|max:190|unique:inventory_items,sku',
            'unit' => 'nullable|string|max:50',
        ]);
        Item::create($data);
        return redirect()->route('inventory.items.index')->with('status','Item created');
    }

    public function edit(Item $item)
    {
        return view('inventory::items.edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        $data = $request->validate([
            'name' => 'required|string|max:190',
            'sku' => 'nullable|string|max:190|unique:inventory_items,sku,' . $item->id,
            'unit' => 'nullable|string|max:50',
        ]);
        $item->update($data);
        return redirect()->route('inventory.items.index')->with('status','Item updated');
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return back()->with('status','Item deleted');
    }
}
