<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;

class EditClient extends EditRecord
{
    protected static string $resource = ClientResource::class;

    protected ?string $companyName = null;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['company_name'] = $this->record->clientDetails?->company_name;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->companyName = $data['company_name'] ?? null;
        unset($data['company_name']);

        if (empty($data['password'] ?? null)) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $this->record->clientDetails()->updateOrCreate(
            ['user_id' => $this->record->id],
            ['company_name' => $this->companyName]
        );
    }
}
