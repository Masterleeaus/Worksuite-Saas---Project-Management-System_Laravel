<?php

namespace Modules\FSMStock\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMStock\Models\FSMStockCategory;

class StockCategoryController extends Controller
{
    public function index()
    {
        $categories = FSMStockCategory::orderBy('name')->paginate(50);
        return view('fsmstock::stock_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('fsmstock::stock_categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:128',
            'description' => 'nullable|string',
            'active'      => 'nullable|boolean',
        ]);
        $data['active'] = $request->boolean('active', true);
        FSMStockCategory::create($data);

        return redirect()->route('fsmstock.stock-categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = FSMStockCategory::findOrFail($id);
        return view('fsmstock::stock_categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = FSMStockCategory::findOrFail($id);
        $data = $request->validate([
            'name'        => 'required|string|max:128',
            'description' => 'nullable|string',
            'active'      => 'nullable|boolean',
        ]);
        $data['active'] = $request->boolean('active', true);
        $category->update($data);

        return redirect()->route('fsmstock.stock-categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        FSMStockCategory::findOrFail($id)->delete();
        return redirect()->route('fsmstock.stock-categories.index')
            ->with('success', 'Category deleted.');
    }
}
