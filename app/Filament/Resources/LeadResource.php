<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadResource\Pages\CreateLead;
use App\Filament\Resources\LeadResource\Pages\EditLead;
use App\Filament\Resources\LeadResource\Pages\ListLeads;
use App\Filament\Resources\LeadResource\RelationManagers\LeadEstimatesRelationManager;
use App\Models\Lead;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LeadResource extends BaseTenantResource
{
    protected static ?string $model = Lead::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationGroup = 'CRM';

    protected static ?string $navigationLabel = 'Leads';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('source_id')->relationship('leadSource', 'type')->searchable()->preload(),
            Forms\Components\Select::make('status_id')->relationship('leadStatus', 'type')->searchable()->preload()->required(),
            Forms\Components\Select::make('agent_id')->relationship('leadAgent', 'user_id')->searchable()->preload(),
            Forms\Components\Select::make('currency_id')->relationship('currency', 'currency_code')->searchable()->preload(),
            Forms\Components\TextInput::make('client_name')->required()->maxLength(191),
            Forms\Components\TextInput::make('client_email')->email()->maxLength(191),
            Forms\Components\TextInput::make('company_name')->maxLength(191),
            Forms\Components\TextInput::make('value')->numeric()->prefix('$'),
            Forms\Components\Select::make('next_follow_up')->options([
                'yes' => 'Yes',
                'no' => 'No',
            ])->default('yes')->required(),
            Forms\Components\Textarea::make('note')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => static::applyTenantScope($query))
            ->columns([
                Tables\Columns\TextColumn::make('client_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('company_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('client_email')->searchable(),
                Tables\Columns\TextColumn::make('leadStatus.type')->label('Status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('leadSource.type')->label('Source')->sortable(),
                Tables\Columns\TextColumn::make('value')->money('USD')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status_id')->relationship('leadStatus', 'type')->label('Status')->searchable()->preload(),
                SelectFilter::make('source_id')->relationship('leadSource', 'type')->label('Source')->searchable()->preload(),
                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['from'] ?? null, fn (Builder $q, $date) => $q->whereDate('leads.created_at', '>=', $date))
                        ->when($data['until'] ?? null, fn (Builder $q, $date) => $q->whereDate('leads.created_at', '<=', $date))),
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

    public static function getRelations(): array
    {
        return [
            LeadEstimatesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLeads::route('/'),
            'create' => CreateLead::route('/create'),
            'edit' => EditLead::route('/{record}/edit'),
        ];
    }
}
