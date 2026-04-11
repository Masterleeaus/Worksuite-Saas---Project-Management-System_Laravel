<?php

namespace Modules\PromotionManagement\Observers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Modules\PromotionManagement\Services\PromotionService;

/**
 * Observes BookingModule Booking model.
 *
 * When a new booking is created, fires the auto-apply promotion logic so that
 * qualifying promotions (auto_apply = true) are attached without requiring the
 * customer to enter a coupon code.
 *
 * Registered in PromotionManagementServiceProvider only when BookingModule is
 * present, so this file is safe to exist even without BookingModule installed.
 */
class BookingObserver
{
    /**
     * Cached result of Schema::hasColumn('bookings', 'discount_id').
     * Evaluated once per request rather than on every booking creation.
     */
    private static ?bool $bookingsHasDiscountId = null;

    public function created($booking): void
    {
        try {
            /** @var PromotionService $service */
            $service = app(PromotionService::class);

            $customerId  = $booking->customer_id ?? null;
            $orderTotal  = (float) ($booking->total_booking_amount ?? $booking->service_cost ?? 0);
            $serviceType = $booking->service_type ?? null;
            $zoneId      = (string) ($booking->zone_id ?? '');

            $result = $service->autoApply(
                $customerId ? (int) $customerId : null,
                $orderTotal,
                $serviceType,
                $zoneId ?: null
            );

            if ($result !== null && isset($result['discount_id'])) {
                // Cache the column-existence check so it only hits the DB once per request
                if (self::$bookingsHasDiscountId === null) {
                    self::$bookingsHasDiscountId = Schema::hasColumn('bookings', 'discount_id');
                }

                if (self::$bookingsHasDiscountId) {
                    $booking->discount_id     = $result['discount_id'];
                    $booking->discount_amount = $result['discount_amount'];
                    $booking->saveQuietly();
                }

                // Increment global redemption counter
                $service->recordRedemption($result['discount_id']);
            }
        } catch (\Throwable $e) {
            Log::warning('PromotionManagement BookingObserver error: ' . $e->getMessage());
        }
    }
}
