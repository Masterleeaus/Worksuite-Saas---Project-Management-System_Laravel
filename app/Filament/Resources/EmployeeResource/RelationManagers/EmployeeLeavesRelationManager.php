<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class EmployeeLeavesRelationManager extends RelationManager
{
    protected static string $relationship = 'leaves';

    protected static ?string $title = 'Leave Records';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('leave_date')->date()->sortable(),
                Tables\Columns\TextColumn::make('type.type_name')->label('Type')->searchable(),
                Tables\Columns\TextColumn::make('duration'),
                Tables\Columns\TextColumn::make('status')->badge(),
            ]);
    }
}
