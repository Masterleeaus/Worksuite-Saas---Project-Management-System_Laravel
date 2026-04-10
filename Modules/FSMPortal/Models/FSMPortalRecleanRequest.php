<?php

namespace Modules\FSMPortal\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\FSMCore\Models\FSMOrder;

class FSMPortalRecleanRequest extends Model
{
    protected $table = 'fsm_portal_reclean_requests';

    public const STATUSES = [
        'pending'  => 'Pending',
        'accepted' => 'Accepted',
        'rejected' => 'Rejected',
    ];

    protected $fillable = [
        'company_id',
        'fsm_order_id',
        'requested_by',
        'reason',
        'status',
        'fsm_activity_id',
    ];

    protected $casts = [
        'company_id'       => 'integer',
        'fsm_order_id'     => 'integer',
        'requested_by'     => 'integer',
        'fsm_activity_id'  => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(FSMOrder::class, 'fsm_order_id');
    }

    public function requestedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'requested_by');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}
