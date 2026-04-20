<?php

namespace Modules\ZeroPay\Filament\Resources\PaymentSessionResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\ZeroPay\Filament\Resources\PaymentSessionResource;

class ListPaymentSessions extends ListRecords
{
    protected static string $resource = PaymentSessionResource::class;
}
