<?php

namespace App\Filament\Resources\ClientResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ClientDocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'clientDocuments';

    protected static ?string $title = 'Documents';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('filename')->searchable(),
                Tables\Columns\TextColumn::make('size'),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }
}
