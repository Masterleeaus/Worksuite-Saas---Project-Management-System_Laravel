<?php

namespace Modules\FSMSales\Services;

use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\RecurringInvoice;
use Carbon\Carbon;
use Modules\FSMCore\Models\FSMOrder;

class InvoiceGenerationService
{
    /**
     * Attempt to auto-generate an invoice for an FSM Order based on its billing policy.
     * Returns the created invoice or null if the policy does not trigger auto-generation.
     */
    public function generateForOrder(FSMOrder $order): ?Invoice
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
    public function createFromOrderCompletion(FSMOrder $order): Invoice
    {
        $terms   = (int) config('fsmsales.payment_terms_days', 14);
        $invoice = Invoice::create([
            'company_id'   => $order->company_id,
            'invoice_number' => $this->nextInvoiceNumber($order->company_id),
            'client_id'    => $this->resolveClientId($order),
            'issue_date' => now()->toDateString(),
            'due_date'     => now()->addDays($terms)->toDateString(),
            'status'       => 'draft',
            'currency_id' => company()?->currency_id ?? null,
            'default_currency_id' => company()?->currency_id ?? null,
            'sub_total' => 0,
            'total' => 0,
            'due_amount' => 0,
            'discount' => 0,
            'discount_type' => 'percent',
            'recurring' => 'no',
            'fsm_order_id' => $order->id,
        ]);

        // Attach the order
        $invoice->fsmOrders()->syncWithoutDetaching([$order->id]);

        // Service charge line
        $amount = $this->resolveServiceAmount($order);
        InvoiceItems::create([
            'company_id'           => $order->company_id,
            'invoice_id' => $invoice->id,
            'fsm_order_id'         => $order->id,
            'type' => 'item',
            'item_name' => 'Service',
            'item_summary' => 'Service – ' . $order->name,
            'quantity'                  => 1,
            'unit_price'           => $amount,
            'amount' => $amount,
        ]);

        // Billable stock lines (FSMStock integration)
        $this->attachBillableStockLines($invoice, $order);

        $this->recalculateInvoiceTotals($invoice);

        // Mark order as invoiced
        $order->is_invoiced = true;
        $order->save();

        return $invoice->fresh(['items']);
    }

    /**
     * Create a draft invoice from timesheet hours × hourly_rate.
     */
    public function createFromOrderTimesheet(FSMOrder $order): ?Invoice
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

        $invoice = Invoice::create([
            'company_id'   => $order->company_id,
            'invoice_number' => $this->nextInvoiceNumber($order->company_id),
            'client_id'    => $this->resolveClientId($order),
            'issue_date' => now()->toDateString(),
            'due_date'     => now()->addDays($terms)->toDateString(),
            'status'       => 'draft',
            'currency_id' => company()?->currency_id ?? null,
            'default_currency_id' => company()?->currency_id ?? null,
            'sub_total' => 0,
            'total' => 0,
            'due_amount' => 0,
            'discount' => 0,
            'discount_type' => 'percent',
            'recurring' => 'no',
            'fsm_order_id' => $order->id,
        ]);

        $invoice->fsmOrders()->syncWithoutDetaching([$order->id]);

        InvoiceItems::create([
            'company_id'           => $order->company_id,
            'invoice_id' => $invoice->id,
            'fsm_order_id'         => $order->id,
            'type' => 'item',
            'item_name' => 'Timesheet',
            'item_summary' => sprintf('Labour – %s (%.2f hrs @ $%.2f/hr)', $order->name, $totalHours, $rate),
            'quantity' => $totalHours,
            'unit_price'           => $rate,
            'amount' => $amount,
        ]);

        $this->attachBillableStockLines($invoice, $order);
        $this->recalculateInvoiceTotals($invoice);

        $order->is_invoiced = true;
        $order->save();

        return $invoice->fresh(['items']);
    }

    /**
     * Bulk-create invoices for all completed, unbilled orders in a date range.
     * Returns the collection of newly created invoices.
     *
     * @param  Carbon|string  $from
     * @param  Carbon|string  $to
     * @return \Illuminate\Support\Collection<Invoice>
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
    ): RecurringInvoice {
        $terms  = (int) config('fsmsales.payment_terms_days', 14);
        $amount = $this->prorateAmount($agreement, $periodStart, $periodEnd, $schedule);

        return RecurringInvoice::create([
            'company_id' => $agreement->company_id,
            'project_id' => null,
            'client_id' => $agreement->partner_id,
            'currency_id' => company()?->currency_id ?? null,
            'issue_date' => $periodStart->toDateString(),
            'next_invoice_date' => $periodEnd->copy()->addDay()->toDateString(),
            'due_date' => $periodEnd->copy()->addDays($terms)->toDateString(),
            'sub_total' => $amount,
            'total' => $amount,
            'discount' => 0,
            'discount_type' => 'percent',
            'status' => 'active',
            'rotation' => $this->mapScheduleToRotation($schedule),
            'note' => 'FSM agreement #' . $agreement->id,
            'show_shipping_address' => 'no',
        ]);
    }

    /**
     * Convert a recurring invoice entry into a proper Invoice (on admin approval).
     */
    public function convertRecurringToInvoice(RecurringInvoice $recurring): Invoice
    {
        $invoice = Invoice::create([
            'company_id'      => $recurring->company_id,
            'invoice_number'  => $this->nextInvoiceNumber($recurring->company_id),
            'client_id'       => $recurring->client_id,
            'issue_date'    => now()->toDateString(),
            'due_date'        => $recurring->due_date?->toDateString(),
            'status'          => 'draft',
            'currency_id' => $recurring->currency_id ?? company()?->currency_id ?? null,
            'default_currency_id' => company()?->currency_id ?? null,
            'sub_total' => 0,
            'total' => 0,
            'due_amount' => 0,
            'discount' => 0,
            'discount_type' => 'percent',
            'recurring' => 'no',
            'invoice_recurring_id' => $recurring->id,
        ]);

        InvoiceItems::create([
            'company_id'           => $recurring->company_id,
            'invoice_id' => $invoice->id,
            'type' => 'item',
            'item_name' => 'Recurring Service',
            'item_summary' => $recurring->note ?: 'Recurring invoice',
            'quantity' => 1,
            'unit_price' => $recurring->total,
            'amount' => $recurring->total,
        ]);

        $this->recalculateInvoiceTotals($invoice);

        return $invoice->fresh(['items']);
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

    private function attachBillableStockLines(Invoice $invoice, FSMOrder $order): void
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
            InvoiceItems::create([
                'company_id'           => $order->company_id,
                'invoice_id' => $invoice->id,
                'fsm_order_id'         => $order->id,
                'type' => 'item',
                'item_name' => 'Stock',
                'item_summary' => $stockLine->product_name ?? 'Consumable',
                'quantity' => (float) ($stockLine->qty ?? 1),
                'unit_price'           => (float) ($stockLine->unit_price ?? 0),
                'amount' => round((float) ($stockLine->qty ?? 1) * (float) ($stockLine->unit_price ?? 0), 2),
            ]);
        }
    }

    private function recalculateInvoiceTotals(Invoice $invoice): void
    {
        $subTotal = (float) $invoice->items()->sum('amount');
        $invoice->sub_total = $subTotal;
        $invoice->total = $subTotal;
        $invoice->due_amount = $subTotal;
        $invoice->save();
    }

    private function nextInvoiceNumber(?int $companyId = null): int
    {
        return Invoice::lastInvoiceNumber($companyId) + 1;
    }

    private function mapScheduleToRotation(string $schedule): string
    {
        return match ($schedule) {
            'quarterly' => 'quarterly',
            'annual' => 'annually',
            default => 'monthly',
        };
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
