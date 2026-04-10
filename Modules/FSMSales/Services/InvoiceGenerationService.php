<?php

namespace Modules\FSMSales\Services;

use Carbon\Carbon;
use Modules\FSMCore\Models\FSMOrder;
use Modules\FSMSales\Models\FSMRecurringInvoice;
use Modules\FSMSales\Models\FSMSalesInvoice;
use Modules\FSMSales\Models\FSMSalesInvoiceLine;

class InvoiceGenerationService
{
    /**
     * Attempt to auto-generate an invoice for an FSM Order based on its billing policy.
     * Returns the created invoice or null if the policy does not trigger auto-generation.
     */
    public function generateForOrder(FSMOrder $order): ?FSMSalesInvoice
    {
        $policy = $order->billing_policy ?? 'manual';

        return match ($policy) {
            'on_completion' => $this->createFromOrderCompletion($order),
            'on_timesheet'  => $this->createFromOrderTimesheet($order),
            default         => null,
        };
    }

    /**
     * Create a draft invoice when an order is marked complete (billing_policy = on_completion).
     */
    public function createFromOrderCompletion(FSMOrder $order): FSMSalesInvoice
    {
        $terms   = (int) config('fsmsales.payment_terms_days', 14);
        $invoice = FSMSalesInvoice::create([
            'company_id'   => $order->company_id,
            'number'       => FSMSalesInvoice::nextNumber(),
            'client_id'    => $this->resolveClientId($order),
            'agreement_id' => $order->agreement_id,
            'invoice_date' => now()->toDateString(),
            'due_date'     => now()->addDays($terms)->toDateString(),
            'status'       => FSMSalesInvoice::STATUS_DRAFT,
        ]);

        // Attach the order
        $invoice->orders()->attach($order->id);

        // Service charge line
        $amount = $this->resolveServiceAmount($order);
        FSMSalesInvoiceLine::create([
            'company_id'           => $order->company_id,
            'fsm_sales_invoice_id' => $invoice->id,
            'fsm_order_id'         => $order->id,
            'line_type'            => FSMSalesInvoiceLine::TYPE_SERVICE,
            'description'          => 'Service – ' . $order->name,
            'qty'                  => 1,
            'unit_price'           => $amount,
            'tax_rate'             => 0,
        ]);

        // Billable stock lines (FSMStock integration)
        $this->attachBillableStockLines($invoice, $order);

        // Mark order as invoiced
        $order->is_invoiced = true;
        $order->save();

        return $invoice->fresh(['lines']);
    }

    /**
     * Create a draft invoice from timesheet hours × hourly_rate.
     */
    public function createFromOrderTimesheet(FSMOrder $order): ?FSMSalesInvoice
    {
        if (!class_exists(\Modules\FSMTimesheet\Models\FSMTimesheetLine::class)) {
            return null;
        }

        $lines = \Modules\FSMTimesheet\Models\FSMTimesheetLine::where('fsm_order_id', $order->id)->get();
        $totalHours = (float) $lines->sum('unit_amount');

        if ($totalHours <= 0) {
            return null;
        }

        $rate    = (float) ($order->hourly_rate ?? 0);
        $amount  = round($totalHours * $rate, 2);
        $terms   = (int) config('fsmsales.payment_terms_days', 14);

        $invoice = FSMSalesInvoice::create([
            'company_id'   => $order->company_id,
            'number'       => FSMSalesInvoice::nextNumber(),
            'client_id'    => $this->resolveClientId($order),
            'agreement_id' => $order->agreement_id,
            'invoice_date' => now()->toDateString(),
            'due_date'     => now()->addDays($terms)->toDateString(),
            'status'       => FSMSalesInvoice::STATUS_DRAFT,
        ]);

        $invoice->orders()->attach($order->id);

        FSMSalesInvoiceLine::create([
            'company_id'           => $order->company_id,
            'fsm_sales_invoice_id' => $invoice->id,
            'fsm_order_id'         => $order->id,
            'line_type'            => FSMSalesInvoiceLine::TYPE_TIMESHEET,
            'description'          => sprintf('Labour – %s (%.2f hrs @ $%.2f/hr)', $order->name, $totalHours, $rate),
            'qty'                  => $totalHours,
            'unit_price'           => $rate,
            'tax_rate'             => 0,
        ]);

        $this->attachBillableStockLines($invoice, $order);

        $order->is_invoiced = true;
        $order->save();

        return $invoice->fresh(['lines']);
    }

    /**
     * Bulk-create invoices for all completed, unbilled orders in a date range.
     * Returns the collection of newly created invoices.
     *
     * @param  Carbon|string  $from
     * @param  Carbon|string  $to
     * @return \Illuminate\Support\Collection<FSMSalesInvoice>
     */
    public function bulkCreateForPeriod(mixed $from, mixed $to, ?int $companyId = null): \Illuminate\Support\Collection
    {
        $fromDate = Carbon::parse($from)->startOfDay();
        $toDate   = Carbon::parse($to)->endOfDay();

        $query = FSMOrder::query()
            ->where('is_invoiced', false)
            ->whereNotNull('date_end')
            ->whereBetween('date_end', [$fromDate, $toDate]);

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        $created = collect();

        foreach ($query->get() as $order) {
            $invoice = $this->createFromOrderCompletion($order);
            $created->push($invoice);
        }

        return $created;
    }

    /**
     * Generate a recurring invoice entry for the given agreement and period.
     */
    public function generateRecurringEntry(
        \Modules\FSMServiceAgreement\Models\FSMServiceAgreement $agreement,
        string $schedule,
        Carbon $periodStart,
        Carbon $periodEnd
    ): FSMRecurringInvoice {
        $terms  = (int) config('fsmsales.payment_terms_days', 14);
        $amount = $this->prorateAmount($agreement, $periodStart, $periodEnd, $schedule);

        return FSMRecurringInvoice::create([
            'company_id'      => $agreement->company_id,
            'agreement_id'    => $agreement->id,
            'client_id'       => $agreement->partner_id,
            'billing_schedule'=> $schedule,
            'period_start'    => $periodStart->toDateString(),
            'period_end'      => $periodEnd->toDateString(),
            'amount'          => $amount,
            'status'          => FSMRecurringInvoice::STATUS_DRAFT,
            'due_date'        => $periodEnd->copy()->addDays($terms)->toDateString(),
        ]);
    }

    /**
     * Convert a recurring invoice entry into a proper FSMSalesInvoice (on admin approval).
     */
    public function convertRecurringToInvoice(FSMRecurringInvoice $recurring): FSMSalesInvoice
    {
        $invoice = FSMSalesInvoice::create([
            'company_id'      => $recurring->company_id,
            'number'          => FSMSalesInvoice::nextNumber(),
            'client_id'       => $recurring->client_id,
            'agreement_id'    => $recurring->agreement_id,
            'invoice_date'    => now()->toDateString(),
            'due_date'        => $recurring->due_date?->toDateString(),
            'status'          => FSMSalesInvoice::STATUS_DRAFT,
            'billing_schedule'=> $recurring->billing_schedule,
        ]);

        FSMSalesInvoiceLine::create([
            'company_id'           => $recurring->company_id,
            'fsm_sales_invoice_id' => $invoice->id,
            'line_type'            => FSMSalesInvoiceLine::TYPE_SERVICE,
            'description'          => sprintf(
                'Service Agreement – %s to %s',
                $recurring->period_start?->format('d M Y'),
                $recurring->period_end?->format('d M Y')
            ),
            'qty'        => 1,
            'unit_price' => $recurring->amount,
            'tax_rate'   => 0,
        ]);

        $recurring->update([
            'fsm_sales_invoice_id' => $invoice->id,
            'status'               => FSMRecurringInvoice::STATUS_SENT,
        ]);

        return $invoice->fresh(['lines']);
    }

    // ── Private helpers ──────────────────────────────────────────────────────

    private function resolveClientId(FSMOrder $order): ?int
    {
        // Try location's client, then the order's person as fallback
        if ($order->location_id && $order->location) {
            $loc = $order->location;
            if (isset($loc->partner_id)) {
                return (int) $loc->partner_id;
            }
        }
        return null;
    }

    private function resolveServiceAmount(FSMOrder $order): float
    {
        if ($order->billing_amount !== null) {
            return (float) $order->billing_amount;
        }

        // Fallback to template base price if available
        if ($order->template_id && $order->template) {
            $template = $order->template;
            if (isset($template->base_price)) {
                return (float) $template->base_price;
            }
        }

        return 0.0;
    }

    private function attachBillableStockLines(FSMSalesInvoice $invoice, FSMOrder $order): void
    {
        // Guard: FSMStock must be installed and have billable lines
        if (!class_exists(\Modules\FSMStock\Models\FSMOrderStockLine::class)) {
            return;
        }

        if (!\Illuminate\Support\Facades\Schema::hasTable('fsm_order_stock_lines')) {
            return;
        }

        $stockLines = \Modules\FSMStock\Models\FSMOrderStockLine::where('fsm_order_id', $order->id)
            ->where('is_billable', true)
            ->get();

        foreach ($stockLines as $stockLine) {
            FSMSalesInvoiceLine::create([
                'company_id'           => $order->company_id,
                'fsm_sales_invoice_id' => $invoice->id,
                'fsm_order_id'         => $order->id,
                'line_type'            => FSMSalesInvoiceLine::TYPE_STOCK,
                'description'          => $stockLine->product_name ?? 'Consumable',
                'qty'                  => (float) ($stockLine->qty ?? 1),
                'unit_price'           => (float) ($stockLine->unit_price ?? 0),
                'tax_rate'             => 0,
                'stock_line_id'        => $stockLine->id,
            ]);
        }
    }

    /**
     * Prorate the agreement value for the billing period.
     */
    private function prorateAmount(
        \Modules\FSMServiceAgreement\Models\FSMServiceAgreement $agreement,
        Carbon $periodStart,
        Carbon $periodEnd,
        string $schedule
    ): float {
        $baseValue = (float) ($agreement->value ?? 0);

        if ($baseValue <= 0) {
            return 0.0;
        }

        // For per_visit, return base value as-is
        if ($schedule === 'per_visit') {
            return $baseValue;
        }

        // Prorate if agreement starts/ends within this period
        $effectiveStart = $agreement->start_date ? max($periodStart, Carbon::parse($agreement->start_date)) : $periodStart;
        $effectiveEnd   = $agreement->end_date   ? min($periodEnd,   Carbon::parse($agreement->end_date))   : $periodEnd;

        if ($effectiveEnd <= $effectiveStart) {
            return 0.0;
        }

        $periodDays    = $periodStart->diffInDays($periodEnd) + 1;
        $effectiveDays = $effectiveStart->diffInDays($effectiveEnd) + 1;

        if ($periodDays <= 0) {
            return $baseValue;
        }

        return round($baseValue * ($effectiveDays / $periodDays), 2);
    }
}
