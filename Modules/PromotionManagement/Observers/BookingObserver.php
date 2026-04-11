<?php

namespace Modules\PromotionManagement\Observers;

use Illuminate\Support\Facades\Log;
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
                // Persist the auto-applied discount on the booking row when the
                // column exists (non-destructive — schema may not always have it).
                if (isset($booking->discount_id) || \Illuminate\Support\Facades\Schema::hasColumn('bookings', 'discount_id')) {
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
