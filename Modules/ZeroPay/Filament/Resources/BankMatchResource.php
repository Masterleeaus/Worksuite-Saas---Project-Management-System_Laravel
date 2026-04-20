<?php

namespace Modules\ZeroPay\Filament\Resources;

use App\Filament\Resources\BaseTenantResource;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\ZeroPay\Filament\Resources\BankMatchResource\Pages\ListBankMatches;
use Modules\ZeroPay\Models\ZeroPayBankMatch;

class BankMatchResource extends BaseTenantResource
{
    protected static ?string $model = ZeroPayBankMatch::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'ZeroPay';
    protected static ?string $navigationLabel = 'Bank Matches';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->sortable(),
            Tables\Columns\TextColumn::make('session.public_token')->label('Session')->limit(16),
            Tables\Columns\TextColumn::make('bank_reference')->searchable(),
            Tables\Columns\TextColumn::make('suggested_amount')->numeric(2),
            Tables\Columns\TextColumn::make('confidence_score')->numeric(2),
            Tables\Columns\TextColumn::make('status')->badge(),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
        ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBankMatches::route('/'),
        ];
    }
}
