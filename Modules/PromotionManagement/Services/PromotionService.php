<?php

namespace Modules\PromotionManagement\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\PromotionManagement\Entities\Coupon;
use Modules\PromotionManagement\Entities\Discount;

/**
 * PromotionService — validates and applies promotion/coupon codes.
 *
 * Supports:
 *  - Percentage discounts (capped at 100 %)
 *  - Fixed-amount discounts (capped at invoice total)
 *  - new_clients_only enforcement
 *  - min_bookings_required (loyalty) enforcement
 *  - max_uses_per_client enforcement
 *  - max_total_uses with race-condition protection (DB lock)
 *  - auto-apply promos (no code required)
 *  - Expiry check at application time (not just at entry)
 */
class PromotionService
{
    /** @var bool|null Cached result of `Schema::hasTable('bookings')` */
    private ?bool $bookingsTableExists = null;

    /** @var bool|null Cached result of `Schema::hasColumn('bookings', 'discount_id')` — unused here but kept for symmetry */
    /**
     * Validate and apply a coupon code to a booking/invoice.
     *
     * @param  string      $couponCode   The coupon code entered by the user.
     * @param  int|null    $customerId   The customer/user applying the coupon.
     * @param  float       $orderTotal   The order/invoice total before discount.
     * @param  string|null $serviceType  Optional service type slug for filter checks.
     * @param  string|null $zoneId       Optional zone id for filter checks.
     * @return array{
     *   success: bool,
     *   message: string,
     *   discount_amount: float,
     *   discount_id: string|null,
     *   coupon_id: string|null,
     * }
     */
    public function apply(
        string  $couponCode,
        ?int    $customerId,
        float   $orderTotal,
        ?string $serviceType = null,
        ?string $zoneId = null
    ): array {
        $coupon = Coupon::with('discount')
            ->where('coupon_code', $couponCode)
            ->where('is_active', true)
            ->first();

        if (!$coupon) {
            return $this->fail(__('promotionmanagement::messages.coupon_not_found'));
        }

        /** @var Discount $discount */
        $discount = $coupon->discount;

        if (!$discount || !$discount->is_active) {
            return $this->fail(__('promotionmanagement::messages.promotion_inactive'));
        }

        return $this->applyDiscount($discount, $coupon, $customerId, $orderTotal, $serviceType, $zoneId);
    }

    /**
     * Find and apply the first matching auto-apply promotion for the given context.
     *
     * Called on BookingCreated events.
     * Note: `promotion_type` is the existing column on the `discounts` table that
     * distinguishes discount rows ('discount') from coupon rows ('coupon').
     * The new `promo_type` column (added by migration) holds the calculation type
     * (percentage | fixed | free_service | bundle) and is separate.
     *
     * @return array|null  Null when no auto-apply promo matched.
     */
    public function autoApply(
        ?int    $customerId,
        float   $orderTotal,
        ?string $serviceType = null,
        ?string $zoneId = null
    ): ?array {
        $discount = Discount::where('auto_apply', true)
            ->where('is_active', true)
            ->where('start_date', '<=', Carbon::today())
            ->where('end_date', '>=', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$discount) {
            return null;
        }

        $result = $this->applyDiscount($discount, null, $customerId, $orderTotal, $serviceType, $zoneId);

        return $result['success'] ? $result : null;
    }

    /**
     * Increment the total_uses counter for a discount (called after confirmed redemption).
     */
    public function recordRedemption(string $discountId): void
    {
        DB::table('discounts')
            ->where('id', $discountId)
            ->increment('total_uses');
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    private function applyDiscount(
        Discount $discount,
        ?Coupon  $coupon,
        ?int     $customerId,
        float    $orderTotal,
        ?string  $serviceType,
        ?string  $zoneId
    ): array {
        // 1. Expiry check (re-validated at application time)
        $today = Carbon::today();
        if ($today->lt(Carbon::parse($discount->start_date)) ||
            $today->gt(Carbon::parse($discount->end_date))) {
            return $this->fail(__('promotionmanagement::messages.promotion_expired'));
        }

        // 2. Service-type filter
        if ($discount->service_type_filter && $serviceType !== null &&
            $discount->service_type_filter !== $serviceType) {
            return $this->fail(__('promotionmanagement::messages.promotion_not_applicable'));
        }

        // 3. Zone filter
        if ($discount->zone_filter && $zoneId !== null &&
            $discount->zone_filter !== $zoneId) {
            return $this->fail(__('promotionmanagement::messages.promotion_not_applicable'));
        }

        // 4. New-clients-only check
        if ($discount->new_clients_only && $customerId !== null) {
            if (!$this->isNewClient($customerId)) {
                return $this->fail(__('promotionmanagement::messages.new_clients_only'));
            }
        }

        // 5. Minimum bookings required (loyalty)
        if ($discount->min_bookings_required > 0 && $customerId !== null) {
            $priorBookings = $this->countPriorBookings($customerId);
            if ($priorBookings < $discount->min_bookings_required) {
                return $this->fail(
                    __('promotionmanagement::messages.min_bookings_required', [
                        'count' => $discount->min_bookings_required,
                    ])
                );
            }
        }

        // 6. Per-client usage limit
        if ($coupon && $discount->max_uses_per_client > 0 && $customerId !== null) {
            $usedByClient = \Modules\PromotionManagement\Entities\CouponCustomer::where('coupon_id', $coupon->id)
                ->where('user_id', $customerId)
                ->count();
            if ($usedByClient >= $discount->max_uses_per_client) {
                return $this->fail(__('promotionmanagement::messages.max_uses_per_client_reached'));
            }
        }

        // 7. Global cap — use DB lock to prevent race conditions
        if ($discount->max_total_uses !== null) {
            try {
                $discountFromDb = DB::transaction(function () use ($discount) {
                    return DB::table('discounts')
                        ->lockForUpdate()
                        ->where('id', $discount->id)
                        ->first();
                });

                if ($discountFromDb && $discountFromDb->total_uses >= $discountFromDb->max_total_uses) {
                    return $this->fail(__('promotionmanagement::messages.promotion_exhausted'));
                }
            } catch (\Throwable $e) {
                Log::warning('PromotionService: failed to check max_total_uses — ' . $e->getMessage());
            }
        }

        // 8. Minimum purchase check
        if ($discount->min_purchase > 0 && $orderTotal < $discount->min_purchase) {
            return $this->fail(
                __('promotionmanagement::messages.min_purchase_not_met', [
                    'amount' => $discount->min_purchase,
                ])
            );
        }

        // 9. Calculate discount amount
        $discountAmount = $this->calculateDiscount($discount, $orderTotal);

        return [
            'success'         => true,
            'message'         => __('promotionmanagement::messages.promotion_applied'),
            'discount_amount' => $discountAmount,
            'discount_id'     => $discount->id,
            'coupon_id'       => $coupon?->id,
        ];
    }

    /**
     * Calculate the actual discount amount, capped appropriately.
     */
    private function calculateDiscount(Discount $discount, float $orderTotal): float
    {
        $type   = $discount->discount_amount_type ?? 'percent'; // 'percent' | 'amount'
        $amount = (float) $discount->discount_amount;

        if ($type === 'percent') {
            // Percentage cannot exceed 100 %
            $pct            = min($amount, 100.0);
            $discountAmount = round($orderTotal * ($pct / 100), 2);
        } else {
            // Fixed discount cannot exceed invoice total
            $discountAmount = min($amount, $orderTotal);
        }

        // Also honour max_discount_amount cap when set
        if (!empty($discount->max_discount_amount) && $discount->max_discount_amount > 0) {
            $discountAmount = min($discountAmount, (float) $discount->max_discount_amount);
        }

        return $discountAmount;
    }

    /**
     * Check whether the customer has no prior confirmed/completed bookings.
     */
    private function isNewClient(int $customerId): bool
    {
        return $this->countPriorBookings($customerId) === 0;
    }

    /**
     * Count prior bookings for a customer.
     * Guards against missing BookingModule gracefully.
     * Caches the table-existence check in a property to avoid repeated queries.
     */
    private function countPriorBookings(int $customerId): int
    {
        try {
            if ($this->bookingsTableExists === null) {
                $this->bookingsTableExists = \Illuminate\Support\Facades\Schema::hasTable('bookings');
            }

            if (!$this->bookingsTableExists) {
                return 0;
            }

            return (int) DB::table('bookings')
                ->where('customer_id', $customerId)
                ->whereIn('booking_status', ['completed', 'accepted', 'ongoing'])
                ->count();
        } catch (\Throwable) {
            return 0;
        }
    }

    private function fail(string $message): array
    {
        return [
            'success'         => false,
            'message'         => $message,
            'discount_amount' => 0.0,
            'discount_id'     => null,
            'coupon_id'       => null,
        ];
    }
}
