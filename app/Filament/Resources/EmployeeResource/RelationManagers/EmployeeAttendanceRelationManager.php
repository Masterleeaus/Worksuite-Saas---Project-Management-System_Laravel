<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class EmployeeAttendanceRelationManager extends RelationManager
{
    protected static string $relationship = 'attendance';

    protected static ?string $title = 'Attendance';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')->date()->sortable(),
                Tables\Columns\TextColumn::make('clock_in_time')->dateTime(),
                Tables\Columns\TextColumn::make('clock_out_time')->dateTime(),
                Tables\Columns\TextColumn::make('late')->badge(),
                Tables\Columns\TextColumn::make('half_day')->badge(),
            ]);
    }
}
