<?php

namespace Modules\ZeroPay\Filament\Resources;

use App\Filament\Resources\BaseTenantResource;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\ZeroPay\Filament\Resources\PaymentSessionResource\Pages\ListPaymentSessions;
use Modules\ZeroPay\Models\ZeroPaySession;

class PaymentSessionResource extends BaseTenantResource
{
    protected static ?string $model = ZeroPaySession::class;

    protected static ?string $navigationIcon = 'heroicon-o-qr-code';
    protected static ?string $navigationGroup = 'ZeroPay';
    protected static ?string $navigationLabel = 'Payment Sessions';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->sortable(),
            Tables\Columns\TextColumn::make('invoice.invoice_number')->label('Invoice')->searchable(),
            Tables\Columns\TextColumn::make('public_token')->label('Token')->searchable()->limit(20),
            Tables\Columns\TextColumn::make('amount')->numeric(2)->sortable(),
            Tables\Columns\TextColumn::make('selected_method')->badge(),
            Tables\Columns\TextColumn::make('status')->badge(),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
        ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPaymentSessions::route('/'),
        ];
    }
}
