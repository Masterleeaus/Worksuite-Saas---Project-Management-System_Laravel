<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ProjectTasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

    protected static ?string $title = 'Tasks';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('heading')->required(),
            Forms\Components\Select::make('priority')->options([
                'low' => 'Low',
                'medium' => 'Medium',
                'high' => 'High',
            ])->default('medium')->required(),
            Forms\Components\Select::make('status')->options([
                'incomplete' => 'Incomplete',
                'completed' => 'Completed',
            ])->default('incomplete')->required(),
            Forms\Components\DatePicker::make('due_date'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('heading')->searchable(),
                Tables\Columns\TextColumn::make('priority')->badge(),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('due_date')->date(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
