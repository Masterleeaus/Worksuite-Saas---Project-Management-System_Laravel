<?php

namespace App\Http\Controllers\Admin\Tenant\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Admin\Tenant\TenantService;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TenantController extends Controller
{
    protected TenantService $tenantService;

    public function __construct(TenantService $tenantService)
    {
        $this->tenantService = $tenantService;
    }

    public function index(): View
    {
        if (!validatePermissions('tenants')) {
            abort(403, 'Unauthorized access');
        }

        $tenants = $this->tenantService->getAllTenants();
        $stats = $this->tenantService->getStatistics();
        
        return view('admin.Tenant.index', compact('tenants', 'stats'));
    }

    public function show(int $id): View
    {
        if (!validatePermissions('tenants/view')) {
            abort(403, 'Unauthorized access');
        }

        $tenant = $this->tenantService->getTenantById($id);
        
        if (!$tenant) {
            abort(404, 'Tenant not found');
        }

        return view('admin.Tenant.show', compact('tenant'));
    }

    public function edit(int $id): View
    {
        if (!validatePermissions('tenants/edit')) {
            abort(403, 'Unauthorized access');
        }

        $tenant = $this->tenantService->getTenantById($id);
        
        if (!$tenant) {
            abort(404, 'Tenant not found');
        }

        return view('admin.Tenant.edit', compact('tenant'));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        if (!validatePermissions('tenants/edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'plan_type' => 'nullable|in:starter,professional,enterprise',
        ]);

        $tenant = $this->tenantService->updateTenant($id, $validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Tenant updated successfully',
                'redirect' => route('admin.tenants')
            ]);
        }

        return redirect()->route('admin.tenants')->with('success', 'Tenant updated');
    }

    public function toggleStatus(int $id): JsonResponse
    {
        if (!validatePermissions('tenants/status')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $tenant = $this->tenantService->toggleStatus($id);

        return response()->json([
            'success' => true,
            'message' => "Tenant {$tenant->status} successfully",
            'status' => $tenant->status
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        if (!validatePermissions('tenants/delete')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $this->tenantService->deleteTenant($id);

        return response()->json([
            'success' => true,
            'message' => 'Tenant deleted successfully'
        ]);
    }

    public function impersonate(int $id): JsonResponse
    {
        if (!validatePermissions('tenants/impersonate')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $tenant = $this->tenantService->getTenantById($id);
        
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant not found'], 404);
        }

        // Store admin session for returning later
        Session::put('admin_impersonating', [
            'admin_id' => Auth::guard('admin')->id(),
            'admin_name' => Auth::guard('admin')->user()->name,
            'tenant_id' => $tenant->ID,
            'started_at' => now()
        ]);

        AuditLog::log(
            'impersonated',
            'Tenant',
            $tenant->ID,
            $tenant->business_name,
            null,
            null,
            "Admin started impersonating tenant: {$tenant->business_name}"
        );

        // In a real app, this would redirect to tenant dashboard
        // For now, we'll just return success
        return response()->json([
            'success' => true,
            'message' => "Now viewing as {$tenant->business_name}",
            'tenant' => $tenant->business_name,
            // 'redirect' => route('tenant.dashboard', $tenant->ID)
        ]);
    }

    public function stopImpersonation(): JsonResponse
    {
        $impersonation = Session::get('admin_impersonating');
        
        if ($impersonation) {
            AuditLog::log(
                'impersonated',
                'Tenant',
                $impersonation['tenant_id'],
                null,
                null,
                null,
                "Admin stopped impersonating tenant"
            );
            
            Session::forget('admin_impersonating');
        }

        return response()->json([
            'success' => true,
            'message' => 'Returned to admin view',
            'redirect' => route('admin.tenants')
        ]);
    }
}
