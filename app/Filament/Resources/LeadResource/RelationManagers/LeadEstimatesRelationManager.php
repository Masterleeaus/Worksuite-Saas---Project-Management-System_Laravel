<?php

namespace App\Filament\Resources\LeadResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class LeadEstimatesRelationManager extends RelationManager
{
    protected static string $relationship = 'estimates';

    protected static ?string $title = 'Estimates';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('estimate_number')->searchable(),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('total')->money('USD'),
                Tables\Columns\TextColumn::make('valid_till')->date(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }
}
