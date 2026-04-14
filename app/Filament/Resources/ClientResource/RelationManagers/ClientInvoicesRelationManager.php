<?php

namespace App\Filament\Resources\ClientResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ClientInvoicesRelationManager extends RelationManager
{
    protected static string $relationship = 'invoices';

    protected static ?string $title = 'Invoices';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')->searchable(),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('total')->money('USD'),
                Tables\Columns\TextColumn::make('due_date')->date(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }
}
