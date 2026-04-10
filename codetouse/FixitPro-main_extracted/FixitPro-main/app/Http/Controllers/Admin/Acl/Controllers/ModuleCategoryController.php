<?php

namespace App\Http\Controllers\Admin\Acl\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Acl\ModuleCategory;
use App\Services\Admin\Acl\Services\ModuleCategoryService;
use Illuminate\Http\Request;

class ModuleCategoryController extends Controller
{
    protected $categoryService;

    public function __construct(ModuleCategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        if (!\validatePermissions('acl/categories')) {
            return redirect()->back()->with('error', 'Permission denied');
        }
        $categories = $this->categoryService->getAllCategories();
        return view('admin.Acl.Category.index', compact('categories'));
    }

    public function create()
    {
        if (!\validatePermissions('acl/categories/add')) {
            return response()->json(['status' => 0, 'msg' => 'Permission denied']);
        }
        return view('admin.Acl.Category.create');
    }

    public function store(Request $request)
    {
        if (!\validatePermissions('acl/categories/add')) {
            return response()->json(['status' => 0, 'msg' => 'Permission denied']);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tbl_module_categories,name',
            'display_order' => 'nullable|integer',
            'active_status' => 'nullable|integer'
        ]);

        try {
            $this->categoryService->createCategory($validated);
            return response()->json(['status' => 1, 'msg' => 'Category created successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'msg' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        if (!\validatePermissions('acl/categories/edit')) {
            return response()->json(['status' => 0, 'msg' => 'Permission denied']);
        }
        $category = \App\Models\Acl\ModuleCategory::findOrFail($id);
        return view('admin.Acl.Category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        if (!\validatePermissions('acl/categories/edit')) {
            return response()->json(['status' => 0, 'msg' => 'Permission denied']);
        }

        $category = ModuleCategory::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tbl_module_categories,name,'.$category->ID.',ID',
            'display_order' => 'nullable|integer',
            'active_status' => 'nullable|integer'
        ]);

        try {
            $this->categoryService->updateCategory($category, $validated);
            return response()->json(['status' => 1, 'msg' => 'Category updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'msg' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        if (!\validatePermissions('acl/categories/delete')) {
            return redirect()->back()->with('error', 'Permission denied');
        }
        try {
            $category = ModuleCategory::findOrFail($id);
            $this->categoryService->deleteCategory($category);
            return redirect()->back()->with('success', 'Category deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
