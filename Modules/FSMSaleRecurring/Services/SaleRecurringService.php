<?php

namespace Modules\FSMSaleRecurring\Services;

use Modules\FSMRecurring\Models\FSMRecurring;
use Modules\FSMSales\Models\FSMSalesInvoice;
use Modules\FSMSales\Models\FSMSalesInvoiceLine;

class SaleRecurringService
{
    /**
     * Get all recurring schedules linked to invoice lines on this invoice.
     */
    public function getRecurringsForInvoice(FSMSalesInvoice $invoice): \Illuminate\Support\Collection
    {
        $lineIds = $invoice->lines()->pluck('id');
        return FSMRecurring::whereIn('invoice_line_id', $lineIds)->get();
    }

    /**
     * Link a recurring schedule to an invoice line.
     */
    public function linkToInvoiceLine(FSMRecurring $recurring, FSMSalesInvoiceLine $line): void
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
