<?php

namespace Modules\ChattingModule\Observers;

use App\Models\UserChat;
use Illuminate\Database\Eloquent\Model;
use Modules\ChattingModule\Models\ChatRoom;

/**
 * Listens to BookingModule booking events and auto-creates a chat thread
 * when a booking is assigned to a serviceman/cleaner.
 *
 * Registered in ChattingModuleServiceProvider only when BookingModule is active.
 */
class BookingObserver
{
    /**
     * Handle the booking "updated" event.
     * Creates a booking-specific chat thread when a serviceman is assigned.
     */
    public function updated(Model $booking): void
    {
        // Only react when a serviceman is being assigned for the first time
        if (
            !$booking->isDirty('serviceman_id') ||
            empty($booking->serviceman_id)
        ) {
            return;
        }

        $bookingId    = $booking->id;         // UUID
        $servicemanId = (int) $booking->serviceman_id;
        $customerId   = $booking->customer_id ?? null;

        // Resolve the integer user_id for the serviceman
        $servicemanUserId = $this->resolveUserId($servicemanId);
        $customerUserId   = $this->resolveUserId($customerId);

        if (!$servicemanUserId || !$customerUserId) {
            return;
        }

        // Avoid duplicate threads
        $exists = UserChat::where('booking_id', $bookingId)
            ->where('channel', 'booking')
            ->exists();

        if ($exists) {
            return;
        }

        // Create initial system message anchoring the booking thread
        UserChat::create([
            'user_one'          => $servicemanUserId,
            'user_id'           => $customerUserId,
            'from'              => $servicemanUserId,
            'to'                => $customerUserId,
            'message'           => 'Booking #' . ($booking->readable_id ?? $bookingId) . ' chat thread started.',
            'message_type'      => 'text',
            'booking_id'        => (string) $bookingId,
            'channel'           => 'booking',
            'is_read'           => false,
            'is_deleted'        => false,
            'notification_sent' => 0,
        ]);

        // Also create a ChatRoom so the booking can be used for group chats if needed
        ChatRoom::firstOrCreate(
            ['booking_id' => (string) $bookingId, 'type' => 'booking'],
            [
                'company_id'  => $booking->company_id ?? null,
                'name'        => 'Booking #' . ($booking->readable_id ?? $bookingId),
                'member_ids'  => [$servicemanUserId, $customerUserId],
                'created_by'  => $servicemanUserId,
            ]
        );
    }

    /**
     * Resolve an integer users.id from a raw user/entity identifier.
     * Handles UUID-keyed provider/customer pivot tables by delegating to
     * the Worksuite User model lookup.
     */
    private function resolveUserId(mixed $id): ?int
    {
        if (empty($id)) {
            return null;
        }

        // UUID strings are at least 32 characters long; numeric IDs are typically ≤ 9 digits.
        // We use a threshold of 10 characters to distinguish between the two formats.
        if (is_numeric($id) && strlen((string) $id) < 10) {
            return (int) $id;
        }

        // UUID-based lookup via the users table
        $user = \App\Models\User::where('id', $id)->first();

        return $user ? (int) $user->id : null;
    }
}
