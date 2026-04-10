<?php

namespace Modules\Communication\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int         $id
 * @property int|null    $company_id
 * @property string      $name
 * @property string      $trigger_event
 * @property int|null    $template_id
 * @property int         $delay_minutes
 * @property string|null $channel
 * @property string      $recipient_type
 * @property string      $status
 */
class CommunicationAutomation extends Model
{
    use SoftDeletes;

    protected $table = 'communication_automations';

    protected $fillable = [
        'company_id',
        'name',
        'trigger_event',
        'template_id',
        'delay_minutes',
        'channel',
        'recipient_type',
        'status',
    ];

    public function template()
    {
        return $this->belongsTo(CommunicationTemplate::class, 'template_id');
    }

    public function scopeForCompany($query, int $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public static function triggerEventLabels(): array
    {
        return [
            'booking_created'    => 'Booking Created',
            'booking_completed'  => 'Booking Completed',
            'booking_cancelled'  => 'Booking Cancelled',
            'payment_received'   => 'Payment Received',
            'cleaner_assigned'   => 'Cleaner Assigned',
            'custom'             => 'Custom',
        ];
    }

    public static function recipientTypeLabels(): array
    {
        return [
            'customer'     => 'Customer',
            'cleaner'      => 'Cleaner / Worker',
            'admin'        => 'Admin',
            'custom_email' => 'Custom Email',
        ];
    }
}
