<?php

namespace App\Filament\Resources\ContractResource\Pages;

use App\Filament\Resources\BaseTenantResource;
use App\Filament\Resources\ContractResource;
use Filament\Resources\Pages\CreateRecord;

class CreateContract extends CreateRecord
{
    protected static string $resource = ContractResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return BaseTenantResource::stampTenantData($data);
    }
}
