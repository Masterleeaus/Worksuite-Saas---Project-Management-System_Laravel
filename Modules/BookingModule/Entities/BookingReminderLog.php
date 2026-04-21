<?php

namespace Modules\BookingModule\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * BookingReminderLog
 *
 * Records every reminder that has been dispatched (sent) so that the same
 * reminder (schedule + lead time + trigger) is never re-sent on job retry
 * or concurrent queue execution.
 *
 * No company global scope — log rows are keyed by schedule_id and must be
 * readable without an authenticated tenant context (queue worker context).
 *
 * @property int    $id
 * @property int    $schedule_id
 * @property int    $company_id
 * @property int    $lead_minutes
 * @property string $trigger      scheduled|assign|reschedule
 * @property \Carbon\Carbon $sent_at
 */
class BookingReminderLog extends Model
{
    public $timestamps = false;

    protected $table = 'booking_reminder_logs';

    protected $fillable = [
        'schedule_id',
        'company_id',
        'lead_minutes',
        'trigger',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];
}
