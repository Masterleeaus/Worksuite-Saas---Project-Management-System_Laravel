<?php

namespace App\Http\Controllers\Api;

use App\Models\Invoice;

class InvoiceController extends BaseTenantIndexController
{
    protected function modelClass(): string
    {
        return Invoice::class;
    }
}
