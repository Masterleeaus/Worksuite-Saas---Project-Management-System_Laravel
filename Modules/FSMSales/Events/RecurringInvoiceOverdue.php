<?php

namespace Modules\FSMSales\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Modules\FSMSales\Models\FSMRecurringInvoice;

class RecurringInvoiceOverdue
{
    use Dispatchable;

    public function __construct(public readonly FSMRecurringInvoice $recurringInvoice) {}
}
