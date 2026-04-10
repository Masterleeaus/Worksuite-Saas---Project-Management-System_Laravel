<?php

namespace Modules\FSMSales\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\FSMCore\Models\FSMOrder;

class FSMSalesInvoice extends Model
{
    protected $table = 'fsm_sales_invoices';

    const STATUS_DRAFT   = 'draft';
    const STATUS_SENT    = 'sent';
    const STATUS_PAID    = 'paid';
    const STATUS_OVERDUE = 'overdue';
    const STATUS_VOID    = 'void';

    protected $fillable = [
        'company_id',
        'number',
        'client_id',
        'agreement_id',
        'invoice_date',
        'due_date',
        'subtotal',
        'tax_total',
        'total',
        'amount_paid',
        'status',
        'billing_schedule',
        'notes',
        'journal_id',
    ];

    protected $casts = [
        'company_id'   => 'integer',
        'client_id'    => 'integer',
        'agreement_id' => 'integer',
        'invoice_date' => 'date',
        'due_date'     => 'date',
        'subtotal'     => 'decimal:2',
        'tax_total'    => 'decimal:2',
        'total'        => 'decimal:2',
        'amount_paid'  => 'decimal:2',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function lines()
    {
        return $this->hasMany(FSMSalesInvoiceLine::class, 'fsm_sales_invoice_id');
    }

    public function orders()
    {
        return $this->belongsToMany(FSMOrder::class, 'fsm_sales_invoice_order', 'fsm_sales_invoice_id', 'fsm_order_id');
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

    public function recurringEntry()
    {
        return $this->hasOne(FSMRecurringInvoice::class, 'fsm_sales_invoice_id');
    }

    // ── Computed ─────────────────────────────────────────────────────────────

    public function getBalanceDueAttribute(): float
    {
        return max(0, (float) $this->total - (float) $this->amount_paid);
    }

    public function isOverdue(): bool
    {
        return $this->due_date !== null
            && $this->due_date->isPast()
            && !in_array($this->status, [self::STATUS_PAID, self::STATUS_VOID]);
    }

    // ── State helpers ────────────────────────────────────────────────────────

    public function statusLabel(): array
    {
        return match ($this->status) {
            self::STATUS_SENT    => ['label' => 'Sent',    'class' => 'bg-info text-dark'],
            self::STATUS_PAID    => ['label' => 'Paid',    'class' => 'bg-success'],
            self::STATUS_OVERDUE => ['label' => 'Overdue', 'class' => 'bg-danger'],
            self::STATUS_VOID    => ['label' => 'Void',    'class' => 'bg-secondary'],
            default              => ['label' => 'Draft',   'class' => 'bg-warning text-dark'],
        };
    }

    /**
     * Recalculate totals from lines and persist.
     */
    public function recalculate(): void
    {
        $subtotal = (float) $this->lines()->sum('line_subtotal');
        $tax      = (float) $this->lines()->sum('line_tax');

        $this->subtotal  = $subtotal;
        $this->tax_total = $tax;
        $this->total     = $subtotal + $tax;
        $this->save();
    }

    /**
     * Generate the next invoice number.
     */
    public static function nextNumber(): string
    {
        $prefix = config('fsmsales.invoice_prefix', 'INV');
        $last   = static::max('id') ?? 0;
        return $prefix . '-' . str_pad((int) $last + 1, 5, '0', STR_PAD_LEFT);
    }
}
