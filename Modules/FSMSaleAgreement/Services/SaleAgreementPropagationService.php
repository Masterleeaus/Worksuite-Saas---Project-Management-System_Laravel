<?php

namespace Modules\FSMSaleAgreement\Services;

use Modules\FSMSales\Models\FSMSalesInvoice;
use Modules\FSMCore\Models\FSMOrder;

class SaleAgreementPropagationService
{
    /**
     * When an invoice has an agreement_id set, push that agreement_id
     * to all FSM orders linked to the invoice (via the pivot table).
     */
    public function propagate(FSMSalesInvoice $invoice): int
    {
        if (!$invoice->agreement_id) {
            return 0;
        }

        $count = 0;

        foreach ($invoice->orders as $order) {
            if ($order->agreement_id !== $invoice->agreement_id) {
                $order->update(['agreement_id' => $invoice->agreement_id]);
                $count++;
            }
        }

        return $count;
    }

    /**
     * Sync agreement_id for all invoices that have an agreement but whose
     * linked orders do not yet have the agreement_id set. Called by artisan command.
     */
    public function syncAll(): int
    {
        $total = 0;

        FSMSalesInvoice::whereNotNull('agreement_id')
            ->with('orders')
            ->each(function (FSMSalesInvoice $invoice) use (&$total) {
                $total += $this->propagate($invoice);
            });

        return $total;
    }
}
