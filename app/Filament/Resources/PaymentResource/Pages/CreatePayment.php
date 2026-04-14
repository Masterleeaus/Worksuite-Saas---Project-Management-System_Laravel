<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\BaseTenantResource;
use App\Filament\Resources\PaymentResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return BaseTenantResource::stampTenantData($data);
    }
}
