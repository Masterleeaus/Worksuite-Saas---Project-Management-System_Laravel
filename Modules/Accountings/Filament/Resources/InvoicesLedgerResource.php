<?php

namespace Modules\Accountings\Filament\Resources;

use App\Filament\Resources\BaseTenantResource;
use App\Models\Invoice;
use App\Providers\TitanPanelProvider;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class InvoicesLedgerResource extends BaseTenantResource
{
    protected static ?string $model = Invoice::class;
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Invoices Ledger';
    protected static ?string $navigationIcon = 'heroicon-o-document-currency-dollar';

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
            Tables\Columns\TextColumn::make('invoice_number')->searchable(),
            Tables\Columns\TextColumn::make('issue_date')->date(),
            Tables\Columns\TextColumn::make('total')->formatStateUsing(fn ($state): string => number_format((float) $state, 2)),
            Tables\Columns\TextColumn::make('due_amount')->formatStateUsing(fn ($state): string => number_format((float) $state, 2)),
            Tables\Columns\TextColumn::make('status')->badge(),
            Tables\Columns\IconColumn::make('exported_to_xero')->boolean(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => InvoicesLedgerResource\Pages\ListInvoicesLedger::route('/'),
        ];
    }
}
