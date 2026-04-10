<?php

namespace App\Http\Controllers\Admin\Plan\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Admin\Plan\PlanService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class PlanController extends Controller
{
    protected PlanService $planService;

    public function __construct(PlanService $planService)
    {
        $this->planService = $planService;
    }

    public function index(): View
    {
        if (!validatePermissions('plans')) {
            abort(403, 'Unauthorized access');
        }

        $plans = $this->planService->getAllPlans();
        
        return view('admin.Plan.index', compact('plans'));
    }

    public function create(): View
    {
        if (!validatePermissions('plans/add')) {
            abort(403, 'Unauthorized access');
        }

        return view('admin.Plan.create');
    }

    public function store(Request $request): JsonResponse
    {
        if (!validatePermissions('plans/add')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:tbl_plans,name',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,yearly',
            'worker_limit' => 'required|integer|min:1',
            'client_limit' => 'nullable|integer|min:1',
            'job_limit' => 'nullable|integer|min:1',
            'features' => 'nullable|string',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer',
        ]);

        $plan = $this->planService->createPlan($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Plan created successfully',
                'redirect' => route('admin.plans')
            ]);
        }

        return redirect()->route('admin.plans')->with('success', 'Plan created');
    }

    public function edit(int $id): View
    {
        if (!validatePermissions('plans/edit')) {
            abort(403, 'Unauthorized access');
        }

        $plan = $this->planService->getPlanById($id);
        
        if (!$plan) {
            abort(404, 'Plan not found');
        }

        return view('admin.Plan.edit', compact('plan'));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        if (!validatePermissions('plans/edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:tbl_plans,name,' . $id . ',ID',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,yearly',
            'worker_limit' => 'required|integer|min:1',
            'client_limit' => 'nullable|integer|min:1',
            'job_limit' => 'nullable|integer|min:1',
            'features' => 'nullable|string',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer',
        ]);

        $plan = $this->planService->updatePlan($id, $validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Plan updated successfully',
                'redirect' => route('admin.plans')
            ]);
        }

        return redirect()->route('admin.plans')->with('success', 'Plan updated');
    }

    public function destroy(int $id): JsonResponse
    {
        if (!validatePermissions('plans/delete')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $this->planService->deletePlan($id);

        return response()->json([
            'success' => true,
            'message' => 'Plan deleted successfully'
        ]);
    }

    public function toggleStatus(int $id): JsonResponse
    {
        if (!validatePermissions('plans/edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $plan = $this->planService->toggleStatus($id);

        return response()->json([
            'success' => true,
            'message' => "Plan " . ($plan->is_active ? 'activated' : 'deactivated'),
            'is_active' => $plan->is_active
        ]);
    }
}
