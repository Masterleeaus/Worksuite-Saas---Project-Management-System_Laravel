<?php

namespace App\Http\Controllers\Admin\Coupon\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Admin\Coupon\CouponService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CouponController extends Controller
{
    protected CouponService $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    public function index(): View
    {
        if (!validatePermissions('coupons')) {
            abort(403, 'Unauthorized access');
        }

        $coupons = $this->couponService->getAllCoupons();
        
        return view('admin.Coupon.index', compact('coupons'));
    }

    public function create(): View
    {
        if (!validatePermissions('coupons/add')) {
            abort(403, 'Unauthorized access');
        }

        $generatedCode = $this->couponService->generateCode();
        
        return view('admin.Coupon.create', compact('generatedCode'));
    }

    public function store(Request $request): JsonResponse
    {
        if (!validatePermissions('coupons/add')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:tbl_coupons,code',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'max_uses_per_user' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
            'is_active' => 'boolean',
        ]);

        $coupon = $this->couponService->createCoupon($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Coupon created successfully',
                'redirect' => route('admin.coupons')
            ]);
        }

        return redirect()->route('admin.coupons')->with('success', 'Coupon created');
    }

    public function edit(int $id): View
    {
        if (!validatePermissions('coupons/edit')) {
            abort(403, 'Unauthorized access');
        }

        $coupon = $this->couponService->getCouponById($id);
        
        if (!$coupon) {
            abort(404, 'Coupon not found');
        }

        return view('admin.Coupon.edit', compact('coupon'));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        if (!validatePermissions('coupons/edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:tbl_coupons,code,' . $id . ',ID',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'max_uses_per_user' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $coupon = $this->couponService->updateCoupon($id, $validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Coupon updated successfully',
                'redirect' => route('admin.coupons')
            ]);
        }

        return redirect()->route('admin.coupons')->with('success', 'Coupon updated');
    }

    public function destroy(int $id): JsonResponse
    {
        if (!validatePermissions('coupons/delete')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $this->couponService->deleteCoupon($id);

        return response()->json([
            'success' => true,
            'message' => 'Coupon deleted successfully'
        ]);
    }

    public function toggleStatus(int $id): JsonResponse
    {
        if (!validatePermissions('coupons/edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $coupon = $this->couponService->toggleStatus($id);

        return response()->json([
            'success' => true,
            'message' => "Coupon " . ($coupon->is_active ? 'activated' : 'deactivated'),
            'is_active' => $coupon->is_active
        ]);
    }

    public function generateCode(): JsonResponse
    {
        $code = $this->couponService->generateCode();

        return response()->json([
            'success' => true,
            'code' => $code
        ]);
    }
}
