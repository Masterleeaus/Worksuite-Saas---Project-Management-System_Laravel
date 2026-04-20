<?php

namespace Modules\FSMSaleStock\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\FSMCore\Models\FSMOrder;

class FSMStockRequisition extends Model
{
    protected $table = 'fsm_stock_requisitions';

    protected $fillable = [
        'company_id',
        'fsm_order_id',
        'invoice_id',
        'requested_by',
        'status',
        'notes',
        'requested_date',
    ];

    protected $casts = [
        'company_id'     => 'integer',
        'fsm_order_id'   => 'integer',
        'invoice_id'     => 'integer',
        'requested_by'   => 'integer',
        'requested_date' => 'date',
    ];

    const STATUS_DRAFT     = 'draft';
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_FULFILLED = 'fulfilled';
    const STATUS_BILLED    = 'billed';
    const STATUS_CANCELLED = 'cancelled';

    public static array $statuses = [
        self::STATUS_DRAFT     => 'Draft',
        self::STATUS_SUBMITTED => 'Submitted',
        self::STATUS_FULFILLED => 'Fulfilled',
        self::STATUS_BILLED    => 'Billed',
        self::STATUS_CANCELLED => 'Cancelled',
    ];

    public function fsmOrder()
    {
        return $this->belongsTo(FSMOrder::class, 'fsm_order_id');
    }

    public function invoice()
    {
        return $this->belongsTo(\App\Models\Invoice::class, 'invoice_id');
    }

    public function requestedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'requested_by');
    }

    public function lines()
    {
        return $this->hasMany(FSMStockRequisitionLine::class, 'requisition_id');
    }

    public function totalCost(): float
    {
        return (float) $this->lines()->sum(DB::raw('quantity * unit_cost'));
    }
}
