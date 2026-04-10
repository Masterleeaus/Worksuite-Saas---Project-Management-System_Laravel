<?php

namespace App\Http\Controllers\Admin\Acl\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Acl\Role;
use App\Services\Admin\Acl\Services\RoleService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index()
    {
        if (!\validatePermissions('acl/roles')) {
            return redirect()->back()->with('error', 'Permission denied');
        }
        $roles = $this->roleService->getAllRoles();
        return view('admin.Acl.Role.index', compact('roles'));
    }

    public function create()
    {
        if (!\validatePermissions('acl/roles/add')) {
            return response()->json(['status' => 0, 'msg' => 'Permission denied']);
        }

        return view('admin.Acl.Role.create');
    }

    public function store(Request $request)
    {
        if (!\validatePermissions('acl/roles/add')) {
            return response()->json(['status' => 0, 'msg' => 'Permission denied']);
        }

        $validated = $request->validate([
            'role_name' => 'required|string|max:255|unique:tbl_roles,role_name',
            'display_order' => 'nullable|integer',
            'active_status' => 'nullable|integer',
            'module_ids' => 'nullable|array',
            'module_ids.*' => 'integer|exists:tbl_modules,ID,active_status,1'
        ]);

        try {
            $this->roleService->createRole($validated);
            return response()->json(['status' => 1, 'msg' => 'Role created successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'msg' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        if (!\validatePermissions('acl/roles/edit')) {
            return response()->json(['status' => 0, 'msg' => 'Permission denied']);
        }
        $role = \App\Models\Acl\Role::with('modules')->findOrFail($id);
        $roleModuleIds = $role->modules->pluck('ID')->toArray();
        $categories = \App\Models\Acl\ModuleCategory::with(['modules' => function($query) {
            $query->where('active_status', 1)->orderBy('display_order', 'asc');
        }])->where('active_status', 1)->orderBy('display_order', 'asc')->get();

        return view('admin.Acl.Role.edit', compact('role', 'categories', 'roleModuleIds'));
    }

    public function update(Request $request, $id)
    {
        if (!\validatePermissions('acl/roles/edit')) {
            return response()->json(['status' => 0, 'msg' => 'Permission denied']);
        }

        $role = Role::findOrFail($id);
        $validated = $request->validate([
            'role_name' => 'required|string|max:255|unique:tbl_roles,role_name,'.$role->ID.',ID',
            'display_order' => 'nullable|integer',
            'active_status' => 'nullable|integer',
            'module_ids' => 'nullable|array',
            'module_ids.*' => 'integer|exists:tbl_modules,ID,active_status,1'
        ]);

        try {
            $this->roleService->updateRole($role, $validated);
            return response()->json(['status' => 1, 'msg' => 'Role updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'msg' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        if (!\validatePermissions('acl/roles/delete')) {
            return redirect()->back()->with('error', 'Permission denied');
        }
        try {
            $role = Role::findOrFail($id);
            $this->roleService->deleteRole($role);
            return redirect()->back()->with('success', 'Role deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
