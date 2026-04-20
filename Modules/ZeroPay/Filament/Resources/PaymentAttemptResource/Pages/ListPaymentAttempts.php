<?php

namespace Modules\ZeroPay\Filament\Resources\PaymentAttemptResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\ZeroPay\Filament\Resources\PaymentAttemptResource;

class ListPaymentAttempts extends ListRecords
{
    protected static string $resource = PaymentAttemptResource::class;
}
