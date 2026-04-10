<?php

namespace Modules\Security\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use App\Models\User;
use Modules\Units\Entities\Unit;

/**
 * AccessLog Entity
 * Tracks real-time access events (badge swipes, entry attempts, access granted/denied)
 * Works with AuditLog to provide 2-layer audit trail
 */
class AccessLog extends BaseModel
{
    use HasCompany;

    protected $table = 'security_access_logs';
    protected $guarded = ['id'];
    const MODULE_NAME = 'security_access_log';

    // Access event types
    const EVENT_BADGE_SWIPE = 'badge_swipe';
    const EVENT_ENTRY_GRANTED = 'entry_granted';
    const EVENT_ENTRY_DENIED = 'entry_denied';
    const EVENT_EXIT = 'exit';
    const EVENT_VEHICLE_ENTRY = 'vehicle_entry';
    const EVENT_PERMIT_PRESENTED = 'permit_presented';
    const EVENT_ALERT = 'alert';

    // Access status
    const STATUS_GRANTED = 'granted';
    const STATUS_DENIED = 'denied';
    const STATUS_PENDING = 'pending';
    const STATUS_ALERT = 'alert';

    protected $fillable = [
        'company_id',
        'unit_id',
        'user_id',
        'access_card_id',
        'inout_permit_id',
        'work_permit_id',
        'parking_id',
        'event_type',
        'status',
        'location',
        'ip_address',
        'user_agent',
        'reason_denied',
        'duration_seconds',
        'timestamp',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function accessCard()
    {
        return $this->belongsTo(AccessCard::class, 'access_card_id');
    }

    public function inOutPermit()
    {
        return $this->belongsTo(InOutPermit::class, 'inout_permit_id');
    }

    public function workPermit()
    {
        return $this->belongsTo(WorkPermit::class, 'work_permit_id');
    }

    public function parking()
    {
        return $this->belongsTo(Parking::class, 'parking_id');
    }

    // Scopes
    public function scopeGranted($query)
    {
        return $query->where('status', self::STATUS_GRANTED);
    }

    public function scopeDenied($query)
    {
        return $query->where('status', self::STATUS_DENIED);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('timestamp', 'desc');
    }

    public function scopeByLocation($query, $location)
    {
        return $query->where('location', $location);
    }

    public function scopeByEventType($query, $eventType)
    {
        return $query->where('event_type', $eventType);
    }

    public function scopeByDateRange($query, $from, $to)
    {
        return $query->whereBetween('timestamp', [$from, $to]);
    }
}
