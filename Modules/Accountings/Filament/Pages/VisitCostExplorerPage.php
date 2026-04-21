<?php

namespace Modules\Accountings\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Modules\Accountings\Actions\FinalizeJobCostAction;
use Modules\Accountings\Entities\VisitCost;

class VisitCostExplorerPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Visit Cost Explorer';
    protected static ?string $navigationIcon  = 'heroicon-o-map';
    protected static string $view             = 'accountings::filament.pages.visit-cost-explorer';

    public function table(Table $table): Table
    {
        return $table
            ->query(VisitCost::query()->where('company_id', auth()->user()?->company_id))
            ->columns([
                TextColumn::make('visit_ref')
                    ->label('Visit Ref')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('site_ref')
                    ->label('Site')
                    ->searchable(),
                TextColumn::make('contract_ref')
                    ->label('Contract')
                    ->searchable(),
                TextColumn::make('labour_cost')
                    ->label('Labour')
                    ->numeric(2),
                TextColumn::make('travel_cost')
                    ->label('Travel')
                    ->numeric(2),
                TextColumn::make('total_cost')
                    ->label('Total Cost')
                    ->numeric(2)
                    ->sortable(),
                TextColumn::make('revenue')
                    ->numeric(2)
                    ->sortable(),
                TextColumn::make('margin_percent')
                    ->label('Margin %')
                    ->formatStateUsing(fn ($state): string => number_format((float) $state, 2) . '%')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'finalized',
                        'warning' => 'calculated',
                        'danger'  => static fn ($state): bool => $state === 'exception',
                    ]),
                TextColumn::make('occurred_at')
                    ->label('Visit Date')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'calculated' => 'Calculated',
                        'finalized'  => 'Finalized',
                        'exception'  => 'Exception',
                    ]),
                SelectFilter::make('negative_margin')
                    ->label('Negative Margin')
                    ->options(['1' => 'Yes'])
                    ->query(fn ($query, $data) => filled($data['value'])
                        ? $query->where('margin_percent', '<', 0)
                        : $query),
            ])
            ->actions([
                TableAction::make('finalize')
                    ->label('Finalize')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (VisitCost $record): bool => $record->status !== 'finalized')
                    ->action(function (VisitCost $record): void {
                        app(FinalizeJobCostAction::class)->execute($record);
                    })
                    ->successNotificationTitle('Visit cost finalized'),
            ])
            ->defaultSort('occurred_at', 'desc');
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
