<?php

namespace Modules\FSMSales\Models;

use Illuminate\Database\Eloquent\Model;

class FSMSalesInvoiceLine extends Model
{
    protected $table = 'fsm_sales_invoice_lines';

    const TYPE_SERVICE    = 'service';
    const TYPE_TIMESHEET  = 'timesheet';
    const TYPE_STOCK      = 'stock';
    const TYPE_EQUIPMENT  = 'equipment';
    const TYPE_OTHER      = 'other';

    protected $fillable = [
        'company_id',
        'fsm_sales_invoice_id',
        'fsm_order_id',
        'line_type',
        'description',
        'qty',
        'unit_price',
        'tax_rate',
        'line_subtotal',
        'line_tax',
        'line_total',
        'stock_line_id',
    ];

    protected $casts = [
        'company_id'           => 'integer',
        'fsm_sales_invoice_id' => 'integer',
        'fsm_order_id'         => 'integer',
        'qty'                  => 'decimal:4',
        'unit_price'           => 'decimal:2',
        'tax_rate'             => 'decimal:4',
        'line_subtotal'        => 'decimal:2',
        'line_tax'             => 'decimal:2',
        'line_total'           => 'decimal:2',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function invoice()
    {
        return $this->belongsTo(FSMSalesInvoice::class, 'fsm_sales_invoice_id');
    }

    public function order()
    {
        return $this->belongsTo(\Modules\FSMCore\Models\FSMOrder::class, 'fsm_order_id');
    }

    // ── Lifecycle ────────────────────────────────────────────────────────────

    protected static function booted(): void
    {
        static::saving(function (self $line) {
            $qty        = (float) $line->qty;
            $unitPrice  = (float) $line->unit_price;
            $taxRate    = (float) $line->tax_rate;

            $subtotal = round($qty * $unitPrice, 2);
            $tax      = round($subtotal * $taxRate, 2);

            $line->line_subtotal = $subtotal;
            $line->line_tax      = $tax;
            $line->line_total    = $subtotal + $tax;
        });

        static::saved(function (self $line) {
            $line->invoice->recalculate();
        });

        static::deleted(function (self $line) {
            $line->invoice->recalculate();
        });
    }
}
