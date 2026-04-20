<?php

namespace Modules\ZeroPay\Filament\Pages;

use App\Filament\Pages\TitanPage;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Modules\ZeroPay\Models\ZeroPaySetting;

class ZeroPaySettingsPage extends TitanPage implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'ZeroPay';
    protected static ?string $navigationLabel = 'ZeroPay Settings';
    protected static ?int $navigationSort = 2;
    protected static string $view = 'zeropay::filament.pages.zeropay-settings-page';

    public ?array $data = [];

    public function mount(): void
    {
        $companyId = auth()->user()?->company_id;
        $settings = ZeroPaySetting::getCompanySettings($companyId);

        $this->form->fill([
            'payid_handle' => $settings['payid_handle'] ?? null,
            'bank_account_name' => $settings['bank_account_name'] ?? null,
            'bank_bsb' => $settings['bank_bsb'] ?? null,
            'bank_account_number' => $settings['bank_account_number'] ?? null,
            'download_expiry_minutes' => $settings['download_expiry_minutes'] ?? (int) config('zeropay.download_expiry_minutes', 1440),
            'ai_email_enabled' => (bool) ($settings['ai_email_enabled'] ?? true),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('payid_handle')->label('PayID Handle')->maxLength(191),
                Forms\Components\TextInput::make('bank_account_name')->label('Bank Account Name')->maxLength(191),
                Forms\Components\TextInput::make('bank_bsb')->label('BSB')->maxLength(30),
                Forms\Components\TextInput::make('bank_account_number')->label('Account Number')->maxLength(60),
                Forms\Components\TextInput::make('download_expiry_minutes')->numeric()->minValue(10)->required(),
                Forms\Components\Toggle::make('ai_email_enabled')->label('Enable AI email follow-ups'),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $companyId = auth()->user()?->company_id;

        if (!$companyId) {
            return;
        }

        ZeroPaySetting::query()->updateOrCreate(
            ['company_id' => $companyId],
            ['settings' => $this->data]
        );

        Notification::make()->title('ZeroPay settings saved')->success()->send();
    }
}
