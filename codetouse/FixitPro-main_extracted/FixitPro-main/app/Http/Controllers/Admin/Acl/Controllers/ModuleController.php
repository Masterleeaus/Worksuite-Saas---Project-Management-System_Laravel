<?php

namespace App\Http\Controllers\Admin\Acl\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Acl\Module;
use App\Models\Acl\ModuleCategory;
use App\Services\Admin\Acl\Services\ModuleService;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    protected $moduleService;

    public function __construct(ModuleService $moduleService)
    {
        $this->moduleService = $moduleService;
    }

    public function index(Request $request)
    {
        if (!\validatePermissions('acl/modules')) {
            return redirect()->back()->with('error', 'Permission denied');
        }
        
        $modules = $this->moduleService->getAllModules();
        
        return view('admin.Acl.Module.index', compact('modules'));
    }

    public function create()
    {
        if (!\validatePermissions('acl/modules/add')) {
            return response()->json(['status' => 0, 'msg' => 'Permission denied']);
        }
        $categories = \App\Models\Acl\ModuleCategory::where('active_status', 1)->orderBy('display_order', 'asc')->get();
        return view('admin.Acl.Module.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if (!\validatePermissions('acl/modules/add')) {
            return response()->json(['status' => 0, 'msg' => 'Permission denied']);
        }

        $validated = $request->validate([
            'module_name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tbl_modules,slug',
            'route' => 'required|string|max:255',
            'category_id' => 'required|exists:tbl_module_categories,ID,active_status,1',
            'show_in_menu' => 'nullable|integer',
            'display_order' => 'nullable|integer',
            'active_status' => 'nullable|integer'
        ]);

        try {
            $this->moduleService->createModule($validated);
            return response()->json(['status' => 1, 'msg' => 'Module registered successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'msg' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        if (!\validatePermissions('acl/modules/edit')) {
            return response()->json(['status' => 0, 'msg' => 'Permission denied']);
        }
        $module = \App\Models\Acl\Module::findOrFail($id);
        $categories = \App\Models\Acl\ModuleCategory::where('active_status', 1)->orderBy('display_order', 'asc')->get();
        return view('admin.Acl.Module.edit', compact('module', 'categories'));
    }

    public function update(Request $request, $id)
    {
        if (!\validatePermissions('acl/modules/edit')) {
            return response()->json(['status' => 0, 'msg' => 'Permission denied']);
        }

        $module = Module::findOrFail($id);
        $validated = $request->validate([
            'module_name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tbl_modules,slug,'.$module->ID.',ID',
            'route' => 'required|string|max:255',
            'category_id' => 'required|exists:tbl_module_categories,ID,active_status,1',
            'show_in_menu' => 'nullable|integer',
            'display_order' => 'nullable|integer',
            'active_status' => 'nullable|integer'
        ]);

        try {
            $this->moduleService->updateModule($module, $validated);
            return response()->json(['status' => 1, 'msg' => 'Module updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'msg' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        if (!\validatePermissions('acl/modules/delete')) {
            return redirect()->back()->with('error', 'Permission denied');
        }
        try {
            $module = Module::findOrFail($id);
            $this->moduleService->deleteModule($module);
            return redirect()->back()->with('success', 'Module removed successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
