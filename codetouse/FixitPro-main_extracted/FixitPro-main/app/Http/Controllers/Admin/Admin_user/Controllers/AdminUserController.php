<?php

namespace App\Http\Controllers\Admin\Admin_user\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Admin_user\StoreAdminRequest;
use App\Http\Requests\Admin\Admin_user\UpdateAdminRequest;
use App\Models\Admin;
use App\Models\Acl\Role;
use App\Models\Acl\AdminUserRole;
use App\Services\Admin\Admin_user\AdminUserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    protected AdminUserService $adminUserService;

    public function __construct(AdminUserService $adminUserService)
    {
        $this->adminUserService = $adminUserService;
    }

    public function index(): View
    {
        if (!\validatePermissions('acl/users')) {
            return redirect()->back()->with('error', 'Access Denied: You do not have permission to view Admin Users.');
        }
        $admins = $this->adminUserService->getAllAdmins();
        return view('admin.Admin_user.index', compact('admins'));
    }

    public function create()
    {
        if (!\validatePermissions('acl/users/add')) {
            return response()->json(['status' => 0, 'msg' => 'Permission denied']);
        }
        $roles = Role::where('active_status', 1)->orderBy('display_order', 'ASC')->get();
        return view('admin.Admin_user.create', compact('roles'));
    }

    public function store(StoreAdminRequest $request)
    {
        if (!\validatePermissions('acl/users/add')) {
            if (request()->ajax()) {
                return response()->json(['status' => 0, 'msg' => 'Permission denied']);
            }
            return \ajaxResponse(0, 'Permission denied');
        }
        
        try {
            $input = \sanitizeAllInput($request->validated());
            $this->adminUserService->createAdmin($request, $input);
            
            if (request()->ajax()) {
                return response()->json(['status' => 1, 'msg' => 'Admin created successfully.']);
            }
            
            return \ajaxResponse(1, 'Admin created successfully.');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['status' => 0, 'msg' => $e->getMessage()]);
            }
            return \ajaxResponse(0, $e->getMessage());
        }
    }

    public function edit($id)
    {
        if (!\validatePermissions('acl/users/edit')) {
            return response()->json(['status' => 0, 'msg' => 'Permission denied']);
        }
        $admin_user = \App\Models\Admin::findOrFail($id);
        $roles = Role::where('active_status', 1)->orderBy('display_order', 'ASC')->get();
        
        // Get the first role ID for this admin (assuming 1 role based on user request "designation")
        $currentRole = AdminUserRole::where('admin_ID', $id)->first();
        $currentRoleId = $currentRole ? $currentRole->role_ID : null;

        return view('admin.Admin_user.edit', compact('admin_user', 'roles', 'currentRoleId'));
    }

    public function update(UpdateAdminRequest $request, $id)
    {
        if (!validatePermissions('acl/users/edit')) {
            if (request()->ajax()) {
                return response()->json(['status' => 0, 'msg' => 'Permission denied']);
            }
            return ajaxResponse(0, 'Permission denied');
        }

        try {
            $admin_user = Admin::findOrFail($id);
            $input = \sanitizeAllInput($request->validated());
            $this->adminUserService->updateAdmin($admin_user, $request, $input);
            
            if (request()->ajax()) {
                return response()->json(['status' => 1, 'msg' => 'Admin updated successfully.']);
            }
            
            return \ajaxResponse(1, 'Admin updated successfully.');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['status' => 0, 'msg' => $e->getMessage()]);
            }
            return \ajaxResponse(0, $e->getMessage());
        }
    }

    public function destroy($id)
    {
        if (!validatePermissions('acl/users/delete')) {
            return ajaxResponse(0, 'Permission denied');
        }

        try {
            $admin_user = Admin::findOrFail($id);
            $this->adminUserService->deleteAdmin($admin_user);
            return \ajaxResponse(1, 'Admin deleted successfully.');
        } catch (\Exception $e) {
            return \ajaxResponse(0, $e->getMessage());
        }
    }
}
