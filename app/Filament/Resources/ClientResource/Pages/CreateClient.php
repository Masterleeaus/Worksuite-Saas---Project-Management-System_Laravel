<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use App\Models\Role;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateClient extends CreateRecord
{
    protected static string $resource = ClientResource::class;

    protected ?string $companyName = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->companyName = $data['company_name'] ?? null;
        unset($data['company_name']);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        if (auth()->hasUser() && !isset($data['company_id'])) {
            $data['company_id'] = auth()->user()->company_id;
        }

        $data['locale'] = $data['locale'] ?? 'en';
        $data['email_notifications'] = $data['email_notifications'] ?? 1;

        return $data;
    }

    protected function afterCreate(): void
    {
        $client = $this->record;

        $client->clientDetails()->updateOrCreate(
            ['user_id' => $client->id],
            ['company_name' => $this->companyName]
        );

        $role = Role::query()->where('name', 'client')->first();

        if ($role) {
            $client->attachRole($role->id);
            $client->assignUserRolePermission($role->id);
        }
    }
}
