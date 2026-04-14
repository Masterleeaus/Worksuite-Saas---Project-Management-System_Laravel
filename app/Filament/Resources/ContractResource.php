<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContractResource\Pages\CreateContract;
use App\Filament\Resources\ContractResource\Pages\EditContract;
use App\Filament\Resources\ContractResource\Pages\ListContracts;
use App\Models\Contract;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ContractResource extends BaseTenantResource
{
    protected static ?string $model = Contract::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Sales';

    protected static ?string $navigationLabel = 'Contracts';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('client_id')->relationship('client', 'name')->searchable()->preload()->required(),
            Forms\Components\Select::make('project_id')->relationship('project', 'project_name')->searchable()->preload(),
            Forms\Components\Select::make('contract_type_id')->relationship('contractType', 'name')->searchable()->preload(),
            Forms\Components\Select::make('currency_id')->relationship('currency', 'currency_code')->searchable()->preload(),
            Forms\Components\TextInput::make('subject')->required()->maxLength(191),
            Forms\Components\TextInput::make('amount')->numeric()->prefix('$')->required(),
            Forms\Components\DatePicker::make('start_date')->required(),
            Forms\Components\DatePicker::make('end_date'),
            Forms\Components\Textarea::make('description')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => static::applyTenantScope($query))
            ->columns([
                Tables\Columns\TextColumn::make('subject')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('client.name')->label('Client')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('project.project_name')->label('Project')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('amount')->money('USD')->sortable(),
                Tables\Columns\TextColumn::make('start_date')->date()->sortable(),
                Tables\Columns\TextColumn::make('end_date')->date()->sortable(),
                Tables\Columns\TextColumn::make('state')
                    ->label('Status')
                    ->badge()
                    ->state(fn (Contract $record): string => ($record->end_date && now()->gt($record->end_date)) ? 'expired' : 'active'),
            ])
            ->filters([
                SelectFilter::make('client_id')->relationship('client', 'name')->label('Client')->searchable()->preload(),
                SelectFilter::make('project_id')->relationship('project', 'project_name')->label('Project')->searchable()->preload(),
                Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['from'] ?? null, fn (Builder $q, $date) => $q->whereDate('contracts.start_date', '>=', $date))
                        ->when($data['until'] ?? null, fn (Builder $q, $date) => $q->whereDate('contracts.start_date', '<=', $date))),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                ...(class_exists(\Filament\Tables\Actions\ExportBulkAction::class)
                    ? [\Filament\Tables\Actions\ExportBulkAction::make()]
                    : []),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListContracts::route('/'),
            'create' => CreateContract::route('/create'),
            'edit' => EditContract::route('/{record}/edit'),
        ];
    }
}
