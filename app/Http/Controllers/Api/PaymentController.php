<?php

namespace App\Http\Controllers\Api;

use App\Models\Payment;

class PaymentController extends BaseTenantIndexController
{
    protected function modelClass(): string
    {
        return Payment::class;
    }
}
