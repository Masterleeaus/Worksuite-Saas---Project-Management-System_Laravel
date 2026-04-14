<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class EmployeeTasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

    protected static ?string $title = 'Tasks';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('heading')->searchable(),
                Tables\Columns\TextColumn::make('project.project_name')->label('Project')->searchable(),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('due_date')->date(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }
}
