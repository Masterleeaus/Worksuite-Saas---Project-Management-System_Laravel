<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class EmployeeTimeLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'timeLogs';

    protected static ?string $title = 'Time Logs';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('project.project_name')->label('Project')->searchable(),
                Tables\Columns\TextColumn::make('task.heading')->label('Task')->searchable(),
                Tables\Columns\TextColumn::make('start_time')->dateTime(),
                Tables\Columns\TextColumn::make('end_time')->dateTime(),
                Tables\Columns\TextColumn::make('hours_only')->label('Duration'),
                Tables\Columns\TextColumn::make('approved')->badge(),
            ]);
    }
}
