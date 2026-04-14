<?php

namespace Modules\ChattingModule\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modules\ChattingModule\Models\ChatRoom
 *
 * @property int $id
 * @property int|null $company_id
 * @property string $name
 * @property string $type  group|booking|broadcast
 * @property string|null $booking_id  UUID of the related booking
 * @property array $member_ids  JSON array of user IDs
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class ChatRoom extends Model
{
    protected $table = 'chat_rooms';

    protected $fillable = [
        'company_id',
        'name',
        'type',
        'booking_id',
        'member_ids',
        'created_by',
    ];

    protected $casts = [
        'member_ids' => 'array',
    ];

    /**
     * The user who created this room.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * All member User models resolved from the stored JSON array.
     */
    public function members()
    {
        return User::whereIn('id', $this->member_ids ?? [])->get();
    }

    /**
     * Scope: find rooms for a given booking UUID.
     */
    public function scopeForBooking($query, string $bookingId)
    {
        return $query->where('booking_id', $bookingId);
    }

    /**
     * Scope: broadcast rooms only.
     */
    public function scopeBroadcast($query)
    {
        return $query->where('type', 'broadcast');
    }
}
