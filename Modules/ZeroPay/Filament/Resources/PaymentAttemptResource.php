<?php

namespace Modules\ZeroPay\Filament\Resources;

use App\Filament\Resources\BaseTenantResource;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\ZeroPay\Filament\Resources\PaymentAttemptResource\Pages\ListPaymentAttempts;
use Modules\ZeroPay\Models\ZeroPayAttempt;

class PaymentAttemptResource extends BaseTenantResource
{
    protected static ?string $model = ZeroPayAttempt::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'ZeroPay';
    protected static ?string $navigationLabel = 'Payment Attempts';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->sortable(),
            Tables\Columns\TextColumn::make('session.public_token')->label('Session')->limit(16),
            Tables\Columns\TextColumn::make('method')->badge(),
            Tables\Columns\TextColumn::make('rail_type')->badge(),
            Tables\Columns\TextColumn::make('amount')->numeric(2),
            Tables\Columns\TextColumn::make('fee_amount')->numeric(2),
            Tables\Columns\TextColumn::make('status')->badge(),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
        ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPaymentAttempts::route('/'),
        ];
    }
}
