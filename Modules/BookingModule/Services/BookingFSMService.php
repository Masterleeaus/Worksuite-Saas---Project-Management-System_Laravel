<?php

namespace Modules\BookingModule\Services;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Modules\BookingModule\Models\CleaningBooking;

/**
 * BookingFSMService
 *
 * Validates and applies FSM status transitions for CleaningBooking records.
 *
 * Valid state machine:
 *   pending → confirmed → en_route → in_progress → completed → reclean
 *   Any state (except cancelled) → cancelled
 */
class BookingFSMService
{
    /**
     * Attempt to transition $booking to $newStatus.
     *
     * @throws ValidationException  When the transition is not allowed.
     */
    public function transition(CleaningBooking $booking, string $newStatus): CleaningBooking
    {
        $this->assertValidTransition($booking, $newStatus);

        $booking->booking_status = $newStatus;

        // Record clock-in / clock-out timestamps automatically.
        if ($newStatus === 'in_progress' && $booking->cleaner_arrived_at === null) {
            $booking->cleaner_arrived_at = now();
        }

        if ($newStatus === 'completed' && $booking->cleaner_departed_at === null) {
            $booking->cleaner_departed_at = now();
        }

        $booking->save();

        return $booking;
    }

    /**
     * Validate GPS coordinates (server-side — not just client-side).
     *
     * @throws ValidationException
     */
    public function validateCoordinates(?float $lat, ?float $lng): void
    {
        $validator = Validator::make(
            ['lat' => $lat, 'lng' => $lng],
            [
                'lat' => ['nullable', 'numeric', 'between:-90,90'],
                'lng' => ['nullable', 'numeric', 'between:-180,180'],
            ]
        );

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }
    }

    /**
     * Assert that the requested transition is valid.
     *
     * @throws ValidationException
     */
    private function assertValidTransition(CleaningBooking $booking, string $newStatus): void
    {
        if (! $booking->canTransitionTo($newStatus)) {
            throw ValidationException::withMessages([
                'booking_status' => [
                    "Cannot transition from '{$booking->booking_status}' to '{$newStatus}'. "
                    . "Allowed: " . implode(', ', $booking->allowedNextStatuses() ?: ['none']),
                ],
            ]);
        }
    }
}
