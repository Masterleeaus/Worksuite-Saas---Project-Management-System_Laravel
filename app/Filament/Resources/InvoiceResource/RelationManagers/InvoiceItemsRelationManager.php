<?php

namespace App\Filament\Resources\InvoiceResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class InvoiceItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Line Items';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('item_name')->required(),
            Forms\Components\TextInput::make('quantity')->numeric()->required(),
            Forms\Components\TextInput::make('unit_price')->numeric()->required(),
            Forms\Components\TextInput::make('amount')->numeric()->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('item_name')->searchable(),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('unit_price')->money('USD'),
                Tables\Columns\TextColumn::make('amount')->money('USD'),
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
