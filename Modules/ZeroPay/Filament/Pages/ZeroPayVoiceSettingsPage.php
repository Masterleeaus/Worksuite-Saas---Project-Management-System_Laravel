<?php

namespace Modules\ZeroPay\Filament\Pages;

use App\Filament\Pages\TitanPage;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Modules\ZeroPay\Models\ZeroPaySetting;

class ZeroPayVoiceSettingsPage extends TitanPage implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-phone';
    protected static ?string $navigationGroup = 'ZeroPay';
    protected static ?string $navigationLabel = 'Voice Settings';
    protected static ?int $navigationSort = 4;
    protected static string $view = 'zeropay::filament.pages.zeropay-voice-settings-page';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = ZeroPaySetting::getCompanySettings(auth()->user()?->company_id);

        $this->form->fill([
            'voice_enabled' => (bool) ($settings['voice_enabled'] ?? false),
            'voice_provider' => (string) ($settings['voice_provider'] ?? 'twilio'),
            'voice_max_attempts' => (int) ($settings['voice_max_attempts'] ?? 3),
            'voice_call_window' => (string) ($settings['voice_call_window'] ?? '09:00-17:00'),
            'voice_do_not_call' => (bool) ($settings['voice_do_not_call'] ?? false),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('voice_enabled')->label('Enable AI voice follow-ups'),
                Forms\Components\Select::make('voice_provider')->options([
                    'twilio' => 'Twilio',
                    'elevenlabs' => 'ElevenLabs',
                    'google' => 'Google Voice Stack',
                ])->required(),
                Forms\Components\TextInput::make('voice_max_attempts')->numeric()->minValue(1)->maxValue(20)->required(),
                Forms\Components\TextInput::make('voice_call_window')->label('Call Window')->maxLength(50),
                Forms\Components\Toggle::make('voice_do_not_call')->label('Enable Do-Not-Call logic'),
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

        Notification::make()->title('Voice settings saved')->success()->send();
    }
}
