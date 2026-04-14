<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ProjectTimeLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'times';

    protected static ?string $title = 'Time Logs';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Employee')->searchable(),
                Tables\Columns\TextColumn::make('task.heading')->label('Task')->searchable(),
                Tables\Columns\TextColumn::make('start_time')->dateTime(),
                Tables\Columns\TextColumn::make('end_time')->dateTime(),
                Tables\Columns\TextColumn::make('hours_only')->label('Duration'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }
}
