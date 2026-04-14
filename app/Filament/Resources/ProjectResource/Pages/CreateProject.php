<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (auth()->hasUser() && !isset($data['company_id'])) {
            $data['company_id'] = auth()->user()->company_id;
        }

        return $data;
    }
}
