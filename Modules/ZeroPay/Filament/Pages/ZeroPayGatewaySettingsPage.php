<?php

namespace Modules\ZeroPay\Filament\Pages;

use App\Filament\Pages\TitanPage;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Modules\ZeroPay\Models\ZeroPaySetting;

class ZeroPayGatewaySettingsPage extends TitanPage implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'ZeroPay';
    protected static ?string $navigationLabel = 'Gateway Settings';
    protected static ?int $navigationSort = 3;
    protected static string $view = 'zeropay::filament.pages.zeropay-gateway-settings-page';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = ZeroPaySetting::getCompanySettings(auth()->user()?->company_id);

        $this->form->fill([
            'enable_payid' => (bool) ($settings['enable_payid'] ?? true),
            'enable_bank_transfer' => (bool) ($settings['enable_bank_transfer'] ?? true),
            'enable_cash' => (bool) ($settings['enable_cash'] ?? true),
            'enable_card' => (bool) ($settings['enable_card'] ?? true),
            'enable_paypal' => (bool) ($settings['enable_paypal'] ?? true),
            'enable_stripe' => (bool) ($settings['enable_stripe'] ?? true),
            'enable_cryptomus' => (bool) ($settings['enable_cryptomus'] ?? false),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('enable_payid')->label('Enable PayID'),
                Forms\Components\Toggle::make('enable_bank_transfer')->label('Enable Bank Transfer'),
                Forms\Components\Toggle::make('enable_cash')->label('Enable Cash'),
                Forms\Components\Toggle::make('enable_card')->label('Enable Card'),
                Forms\Components\Toggle::make('enable_paypal')->label('Enable PayPal'),
                Forms\Components\Toggle::make('enable_stripe')->label('Enable Stripe'),
                Forms\Components\Toggle::make('enable_cryptomus')->label('Enable Cryptomus'),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $companyId = auth()->user()?->company_id;

        if (!$companyId) {
            return;
        }

        $existing = ZeroPaySetting::getCompanySettings($companyId);

        ZeroPaySetting::query()->updateOrCreate(
            ['company_id' => $companyId],
            ['settings' => array_merge($existing, $this->data)]
        );

        Notification::make()->title('Gateway settings saved')->success()->send();
    }
}
