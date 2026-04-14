<?php

namespace App\Filament\Resources\EstimateResource\Pages;

use App\Filament\Resources\BaseTenantResource;
use App\Filament\Resources\EstimateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEstimate extends CreateRecord
{
    protected static string $resource = EstimateResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $subTotal = collect($data['items'] ?? [])->sum(fn (array $item): float => (float) ($item['amount'] ?? 0));
        $discount = (float) ($data['discount'] ?? 0);

        $data['sub_total'] = $subTotal;
        $data['total'] = max($subTotal - $discount, 0);

        return BaseTenantResource::stampTenantData($data);
    }
}
