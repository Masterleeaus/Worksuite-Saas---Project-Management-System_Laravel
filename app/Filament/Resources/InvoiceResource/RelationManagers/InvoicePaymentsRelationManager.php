<?php

namespace App\Filament\Resources\InvoiceResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class InvoicePaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payment';

    protected static ?string $title = 'Payments';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('amount')->numeric()->required(),
            Forms\Components\Select::make('status')->options([
                'pending' => 'Pending',
                'complete' => 'Complete',
                'failed' => 'Failed',
            ])->default('pending')->required(),
            Forms\Components\DateTimePicker::make('paid_on'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('amount')->money('USD'),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('paid_on')->dateTime(),
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
