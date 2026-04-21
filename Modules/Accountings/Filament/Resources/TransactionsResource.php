<?php

namespace Modules\Accountings\Filament\Resources;

use App\Filament\Resources\BaseTenantResource;
use App\Providers\TitanPanelProvider;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Accountings\Entities\FinancialTransaction;

class TransactionsResource extends BaseTenantResource
{
    protected static ?string $model = FinancialTransaction::class;
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Transactions';
    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    public static function canAccess(): bool
    {
        return TitanPanelProvider::canAccess();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('transaction_type')->label('Type')->badge(),
            Tables\Columns\TextColumn::make('reference')->searchable(),
            Tables\Columns\TextColumn::make('amount')->money('USD', divideBy: 1),
            Tables\Columns\TextColumn::make('site_ref')->label('Site'),
            Tables\Columns\TextColumn::make('contract_ref')->label('Contract'),
            Tables\Columns\TextColumn::make('occurred_at')->dateTime(),
            Tables\Columns\TextColumn::make('status')->badge(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => TransactionsResource\Pages\ListTransactions::route('/'),
        ];
    }
}
