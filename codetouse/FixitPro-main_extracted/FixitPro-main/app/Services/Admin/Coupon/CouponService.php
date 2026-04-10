<?php

namespace App\Services\Admin\Coupon;

use App\Models\Coupon;
use App\Models\AuditLog;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CouponService
{
    public function getAllCoupons(): Collection
    {
        return Coupon::latest()->get();
    }

    public function getValidCoupons(): Collection
    {
        return Coupon::valid()->get();
    }

    public function getCouponById(int $id): ?Coupon
    {
        return Coupon::find($id);
    }

    public function getCouponByCode(string $code): ?Coupon
    {
        return Coupon::where('code', strtoupper($code))->first();
    }

    public function createCoupon(array $data): Coupon
    {
        $data['code'] = strtoupper($data['code'] ?? $this->generateCode());

        $coupon = Coupon::create($data);

        AuditLog::log(
            'created',
            'Coupon',
            $coupon->ID,
            $coupon->code,
            null,
            $coupon->toArray(),
            "Created coupon: {$coupon->code}"
        );

        return $coupon;
    }

    public function updateCoupon(int $id, array $data): Coupon
    {
        $coupon = Coupon::findOrFail($id);
        $oldValues = $coupon->toArray();

        if (isset($data['code'])) {
            $data['code'] = strtoupper($data['code']);
        }

        $coupon->update($data);

        AuditLog::log(
            'updated',
            'Coupon',
            $coupon->ID,
            $coupon->code,
            $oldValues,
            $coupon->fresh()->toArray(),
            "Updated coupon: {$coupon->code}"
        );

        return $coupon->fresh();
    }

    public function deleteCoupon(int $id): bool
    {
        $coupon = Coupon::findOrFail($id);

        AuditLog::log(
            'deleted',
            'Coupon',
            $coupon->ID,
            $coupon->code,
            $coupon->toArray(),
            null,
            "Deleted coupon: {$coupon->code}"
        );

        return $coupon->delete();
    }

    public function toggleStatus(int $id): Coupon
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->update(['is_active' => !$coupon->is_active]);

        return $coupon->fresh();
    }

    public function generateCode(int $length = 8): string
    {
        do {
            $code = strtoupper(Str::random($length));
        } while (Coupon::where('code', $code)->exists());

        return $code;
    }

    public function validateCoupon(string $code, float $orderAmount = 0): array
    {
        $coupon = $this->getCouponByCode($code);

        if (!$coupon) {
            return ['valid' => false, 'message' => 'Coupon not found'];
        }

        if (!$coupon->isValid()) {
            return ['valid' => false, 'message' => 'Coupon is no longer valid'];
        }

        if ($coupon->min_order_amount && $orderAmount < $coupon->min_order_amount) {
            return [
                'valid' => false,
                'message' => "Minimum order amount is $" . number_format($coupon->min_order_amount, 2)
            ];
        }

        return [
            'valid' => true,
            'coupon' => $coupon,
            'discount' => $coupon->formatted_discount
        ];
    }
}
