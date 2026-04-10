<?php

namespace Modules\BookingModule\Listeners;

use Modules\BookingModule\Events\BookingRequested;
use Modules\BookingModule\Services\Ai\TitanZeroBridge;
use Modules\BookingModule\Services\Ai\ProposalRunner;

/**
 * Emits a minimal, bounded signal to TitanZero when a booking is requested.
 *
 * Safe-by-default:
 * - No hard dependency on TitanZero (bridge returns ok=false if not installed)
 * - No side-effects in the booking domain if AI is unavailable
 */
class EmitBookingRequestedSignalToTitanZero
{
    public function handle(BookingRequested $event): void
    {
        $booking = $event->booking;

        $tenantId = null;
        if (isset($booking->company_id) && is_numeric($booking->company_id)) {
            $tenantId = (int) $booking->company_id;
        }

        app(TitanZeroBridge::class)->ingestSignal([
            'type' => 'booking_requested',
            'payload' => [
                'booking_id'   => $booking->id ?? null,
                'readable_id'  => $booking->readable_id ?? null,
                'customer_id'  => $booking->customer_id ?? null,
                'provider_id'  => $booking->provider_id ?? null,
                'zone_id'      => $booking->zone_id ?? null,
                'booking_status' => $booking->booking_status ?? null,
                'is_paid'      => $booking->is_paid ?? null,
                'payment_method' => $booking->payment_method ?? null,
            ],
        ], $tenantId);

// Ask TitanZero for proposals (safe baseline: log-only).
app(ProposalRunner::class)->proposeAndLog([
    'module' => 'BookingModule',
    'event'  => 'booking_requested',
    'entity_type' => 'booking',
    'entity_id' => $booking->id ?? null,
    'facts'  => [
        'readable_id' => $booking->readable_id ?? null,
        'booking_status' => $booking->booking_status ?? null,
        'is_paid' => $booking->is_paid ?? null,
        'payment_method' => $booking->payment_method ?? null,
    ],
], $tenantId);
    }
}
