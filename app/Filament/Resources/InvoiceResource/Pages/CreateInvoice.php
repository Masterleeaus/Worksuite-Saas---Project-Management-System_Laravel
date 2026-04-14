<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\BaseTenantResource;
use App\Filament\Resources\InvoiceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $subTotal = collect($data['items'] ?? [])->sum(fn (array $item): float => (float) ($item['amount'] ?? 0));
        $discount = (float) ($data['discount'] ?? 0);
        $total = max($subTotal - $discount, 0);

        $data['sub_total'] = $subTotal;
        $data['total'] = $total;
        $data['due_amount'] = $total;

        return BaseTenantResource::stampTenantData($data);
    }
}
