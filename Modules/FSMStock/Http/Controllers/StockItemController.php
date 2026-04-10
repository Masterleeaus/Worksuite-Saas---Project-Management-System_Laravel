<?php

namespace Modules\FSMStock\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMStock\Models\FSMStockCategory;
use Modules\FSMStock\Models\FSMStockItem;

class StockItemController extends Controller
{
    public function index()
    {
        $items = FSMStockItem::with('category')->orderBy('name')->paginate(50);
        return view('fsmstock::stock_items.index', compact('items'));
    }

    public function create()
    {
        $categories = FSMStockCategory::orderBy('name')->get();
        return view('fsmstock::stock_items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:128',
            'category_id' => 'nullable|integer|exists:fsm_stock_categories,id',
            'description' => 'nullable|string',
            'unit'        => 'nullable|string|max:32',
            'current_qty' => 'nullable|numeric|min:0',
            'min_qty'     => 'nullable|numeric|min:0',
            'cost_price'  => 'nullable|numeric|min:0',
            'supplier'    => 'nullable|string|max:191',
            'active'      => 'nullable|boolean',
        ]);
        $data['active'] = $request->boolean('active', true);
        FSMStockItem::create($data);

        return redirect()->route('fsmstock.stock-items.index')
            ->with('success', 'Stock item created successfully.');
    }

    public function edit($id)
    {
        $item       = FSMStockItem::findOrFail($id);
        $categories = FSMStockCategory::orderBy('name')->get();
        return view('fsmstock::stock_items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $item = FSMStockItem::findOrFail($id);
        $data = $request->validate([
            'name'        => 'required|string|max:128',
            'category_id' => 'nullable|integer|exists:fsm_stock_categories,id',
            'description' => 'nullable|string',
            'unit'        => 'nullable|string|max:32',
            'current_qty' => 'nullable|numeric|min:0',
            'min_qty'     => 'nullable|numeric|min:0',
            'cost_price'  => 'nullable|numeric|min:0',
            'supplier'    => 'nullable|string|max:191',
            'active'      => 'nullable|boolean',
        ]);
        $data['active'] = $request->boolean('active', true);
        $item->update($data);

        return redirect()->route('fsmstock.stock-items.index')
            ->with('success', 'Stock item updated successfully.');
    }

    public function destroy($id)
    {
        FSMStockItem::findOrFail($id)->delete();
        return redirect()->route('fsmstock.stock-items.index')
            ->with('success', 'Stock item deleted.');
    }
}
