<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class EmployeeProjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'member';

    protected static ?string $title = 'Projects';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('project.project_name')->label('Project')->searchable(),
                Tables\Columns\TextColumn::make('project.status')->badge(),
                Tables\Columns\TextColumn::make('project.deadline')->date(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }
}
