<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EstimateResource\Pages\CreateEstimate;
use App\Filament\Resources\EstimateResource\Pages\EditEstimate;
use App\Filament\Resources\EstimateResource\Pages\ListEstimates;
use App\Models\Estimate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EstimateResource extends BaseTenantResource
{
    protected static ?string $model = Estimate::class;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $navigationGroup = 'Sales';

    protected static ?string $navigationLabel = 'Estimates';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('client_id')->relationship('client', 'name')->searchable()->preload()->required(),
            Forms\Components\Select::make('project_id')->relationship('project', 'project_name')->searchable()->preload(),
            Forms\Components\DatePicker::make('valid_till')->required(),
            Forms\Components\Select::make('status')->options([
                'waiting' => 'Waiting',
                'sent' => 'Sent',
                'accepted' => 'Accepted',
                'declined' => 'Declined',
                'draft' => 'Draft',
                'canceled' => 'Canceled',
            ])->default('waiting')->required(),
            Forms\Components\Select::make('discount_type')->options(['percent' => 'Percent', 'fixed' => 'Fixed'])->default('percent'),
            Forms\Components\TextInput::make('discount')->numeric()->default(0),
            Forms\Components\Select::make('calculate_tax')->options([
                'after_discount' => 'After Discount',
                'before_discount' => 'Before Discount',
            ])->default('after_discount')->required(),
            Forms\Components\Repeater::make('items')
                ->relationship('items')
                ->schema([
                    Forms\Components\TextInput::make('item_name')->required(),
                    Forms\Components\TextInput::make('quantity')->numeric()->default(1)->live(onBlur: true),
                    Forms\Components\TextInput::make('unit_price')->numeric()->default(0)->prefix('$')->live(onBlur: true),
                    Forms\Components\TextInput::make('amount')
                        ->numeric()
                        ->readOnly()
                        ->dehydrated()
                        ->afterStateHydrated(fn (Set $set, Get $get) => static::syncLineItemAmount($set, $get))
                        ->afterStateUpdated(fn (Set $set, Get $get) => static::syncLineItemAmount($set, $get)),
                ])
                ->columnSpanFull(),
            Forms\Components\Placeholder::make('subtotal_preview')
                ->label('Subtotal Preview')
                ->content(fn (Get $get): string => number_format(collect($get('items') ?? [])->sum(fn (array $item): float => (float) ($item['amount'] ?? 0)), 2)),
            Forms\Components\TextInput::make('sub_total')->numeric()->readOnly()->dehydrated(),
            Forms\Components\TextInput::make('total')->numeric()->readOnly()->dehydrated(),
            Forms\Components\Textarea::make('note')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => static::applyTenantScope($query))
            ->columns([
                Tables\Columns\TextColumn::make('estimate_number')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('client.name')->label('Client')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('project.project_name')->label('Project')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('total')->money('USD')->sortable(),
                Tables\Columns\TextColumn::make('valid_till')->date()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'waiting' => 'Waiting',
                    'sent' => 'Sent',
                    'accepted' => 'Accepted',
                    'declined' => 'Declined',
                    'draft' => 'Draft',
                    'canceled' => 'Canceled',
                ]),
                SelectFilter::make('client_id')->relationship('client', 'name')->label('Client')->searchable()->preload(),
                SelectFilter::make('project_id')->relationship('project', 'project_name')->label('Project')->searchable()->preload(),
                Filter::make('valid_till')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['from'] ?? null, fn (Builder $q, $date) => $q->whereDate('estimates.valid_till', '>=', $date))
                        ->when($data['until'] ?? null, fn (Builder $q, $date) => $q->whereDate('estimates.valid_till', '<=', $date))),
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
            'index' => ListEstimates::route('/'),
            'create' => CreateEstimate::route('/create'),
            'edit' => EditEstimate::route('/{record}/edit'),
        ];
    }

    protected static function syncLineItemAmount(Set $set, Get $get): void
    {
        $set('amount', ((float) ($get('quantity') ?: 0)) * ((float) ($get('unit_price') ?: 0)));
    }
}
