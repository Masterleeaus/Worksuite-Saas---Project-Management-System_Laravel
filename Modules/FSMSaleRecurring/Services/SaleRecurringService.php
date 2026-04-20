<?php

namespace Modules\FSMSaleRecurring\Services;

use App\Models\Invoice;
use App\Models\InvoiceItems;
use Modules\FSMRecurring\Models\FSMRecurring;

class SaleRecurringService
{
    /**
     * Get all recurring schedules linked to invoice lines on this invoice.
     */
    public function getRecurringsForInvoice(Invoice $invoice): \Illuminate\Support\Collection
    {
        $lineIds = $invoice->lines()->pluck('id');
        return FSMRecurring::whereIn('invoice_line_id', $lineIds)->get();
    }

    /**
     * Link a recurring schedule to an invoice line.
     */
    public function linkToInvoiceLine(FSMRecurring $recurring, InvoiceItems $line): void
    {
        $recurring->update(['invoice_line_id' => $line->id]);
    }

    /**
     * Unlink a recurring schedule from its invoice line.
     */
    public function unlink(FSMRecurring $recurring): void
    {
        $recurring->update(['invoice_line_id' => null]);
    }
}
