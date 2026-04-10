<?php

namespace Modules\ZoneManagement\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\ZoneManagement\Entities\ZoneCheckIn;

/**
 * Fired when a cleaner checks in to a job site.
 * Broadcast over a private admin channel so the dispatch map refreshes.
 */
class CleanerCheckedIn implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public ZoneCheckIn $checkIn) {}

    public function broadcastOn(): Channel
    {
        return new Channel('admin.dispatch');
    }

    public function broadcastAs(): string
    {
        return 'cleaner.checked-in';
    }

    public function broadcastWith(): array
    {
        return [
            'check_in_id'  => $this->checkIn->id,
            'user_id'      => $this->checkIn->user_id,
            'booking_id'   => $this->checkIn->booking_id,
            'lat'          => $this->checkIn->check_in_lat,
            'lng'          => $this->checkIn->check_in_lng,
            'is_verified'  => $this->checkIn->is_verified,
            'checked_in_at'=> (string) $this->checkIn->checked_in_at,
        ];
    }
}
