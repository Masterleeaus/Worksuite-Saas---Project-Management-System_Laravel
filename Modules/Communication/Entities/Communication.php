<?php

namespace Modules\Communication\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int         $id
 * @property int|null    $company_id
 * @property string      $type
 * @property int|null    $from_user_id
 * @property string|null $from_address
 * @property int|null    $to_user_id
 * @property string|null $to_address
 * @property int|null    $customer_id
 * @property string|null $booking_id
 * @property int|null    $template_id
 * @property string|null $subject
 * @property string      $body
 * @property string      $status
 * @property \Carbon\Carbon|null $sent_at
 * @property \Carbon\Carbon|null $read_at
 */
class Communication extends Model
{
    use SoftDeletes;

    protected $table = 'communications';

    protected $fillable = [
        'company_id',
        'type',
        'from_user_id',
        'from_address',
        'to_user_id',
        'to_address',
        'customer_id',
        'booking_id',
        'template_id',
        'subject',
        'body',
        'status',
        'sent_at',
        'read_at',
        'provider_response',
    ];

    protected $casts = [
        'sent_at'           => 'datetime',
        'read_at'           => 'datetime',
        'provider_response' => 'array',
    ];

    /** Scope: filter by company */
    public function scopeForCompany($query, int $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /** Scope: filter by channel type */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
