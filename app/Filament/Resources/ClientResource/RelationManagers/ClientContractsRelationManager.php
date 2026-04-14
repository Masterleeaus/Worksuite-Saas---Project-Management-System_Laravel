<?php

namespace App\Filament\Resources\ClientResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ClientContractsRelationManager extends RelationManager
{
    protected static string $relationship = 'contracts';

    protected static ?string $title = 'Contracts';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subject')->searchable(),
                Tables\Columns\TextColumn::make('amount')->money('USD'),
                Tables\Columns\TextColumn::make('start_date')->date(),
                Tables\Columns\TextColumn::make('end_date')->date(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }
}
