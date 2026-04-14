<?php

namespace App\Filament\Resources\DocumentTemplateResource\Pages;

use App\Filament\Resources\BaseTenantResource;
use App\Filament\Resources\DocumentTemplateResource;
use Filament\Resources\Pages\ManageRecords;

class ManageDocumentTemplates extends ManageRecords
{
    protected static string $resource = DocumentTemplateResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return BaseTenantResource::stampTenantData($data);
    }
}
