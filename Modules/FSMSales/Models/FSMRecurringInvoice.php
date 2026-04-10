<?php

namespace Modules\FSMSales\Models;

use Illuminate\Database\Eloquent\Model;

class FSMRecurringInvoice extends Model
{
    protected $table = 'fsm_recurring_invoices';

    const STATUS_DRAFT   = 'draft';
    const STATUS_SENT    = 'sent';
    const STATUS_PAID    = 'paid';
    const STATUS_OVERDUE = 'overdue';

    const SCHEDULE_PER_VISIT  = 'per_visit';
    const SCHEDULE_MONTHLY    = 'monthly';
    const SCHEDULE_QUARTERLY  = 'quarterly';
    const SCHEDULE_ANNUAL     = 'annual';

    protected $fillable = [
        'company_id',
        'agreement_id',
        'fsm_sales_invoice_id',
        'client_id',
        'billing_schedule',
        'period_start',
        'period_end',
        'amount',
        'status',
        'due_date',
        'overdue_notified',
        'notes',
    ];

    protected $casts = [
        'company_id'           => 'integer',
        'agreement_id'         => 'integer',
        'fsm_sales_invoice_id' => 'integer',
        'client_id'            => 'integer',
        'period_start'         => 'date',
        'period_end'           => 'date',
        'amount'               => 'decimal:2',
        'due_date'             => 'date',
        'overdue_notified'     => 'boolean',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function invoice()
    {
        return $this->belongsTo(FSMSalesInvoice::class, 'fsm_sales_invoice_id');
    }

    public function client()
    {
        return $this->belongsTo(\App\Models\User::class, 'client_id');
    }

    public function agreement()
    {
        if (!class_exists(\Modules\FSMServiceAgreement\Models\FSMServiceAgreement::class)) {
            return null;
        }
        return $this->belongsTo(\Modules\FSMServiceAgreement\Models\FSMServiceAgreement::class, 'agreement_id');
    }

    // ── State helpers ────────────────────────────────────────────────────────

    public function isOverdue(): bool
    {
        return $this->due_date !== null
            && $this->due_date->isPast()
            && !in_array($this->status, [self::STATUS_PAID]);
    }

    public function statusLabel(): array
    {
        return match ($this->status) {
            self::STATUS_SENT    => ['label' => 'Sent',    'class' => 'bg-info text-dark'],
            self::STATUS_PAID    => ['label' => 'Paid',    'class' => 'bg-success'],
            self::STATUS_OVERDUE => ['label' => 'Overdue', 'class' => 'bg-danger'],
            default              => ['label' => 'Draft',   'class' => 'bg-warning text-dark'],
        };
    }

    public function scheduleLabel(): string
    {
        return config('fsmsales.billing_schedules.' . $this->billing_schedule, ucfirst($this->billing_schedule));
    }
}
