<?php

namespace Modules\Accountings\Filament\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Modules\Accountings\Entities\ProfitabilitySnapshot;
use Modules\Accountings\Jobs\RecalculateSiteProfitabilityJob;

class SiteProfitabilityPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Site Profitability';
    protected static ?string $navigationIcon  = 'heroicon-o-building-office';
    protected static string $view             = 'accountings::filament.pages.site-profitability';

    public function table(Table $table): Table
    {
        $companyId = auth()->user()?->company_id;

        return $table
            ->query(
                ProfitabilitySnapshot::query()
                    ->where('company_id', $companyId)
                    ->where('scope_type', 'site')
            )
            ->columns([
                TextColumn::make('scope_ref')
                    ->label('Site Ref')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('period_start')
                    ->label('From')
                    ->date()
                    ->sortable(),
                TextColumn::make('period_end')
                    ->label('To')
                    ->date(),
                TextColumn::make('revenue')
                    ->numeric(2)
                    ->sortable(),
                TextColumn::make('cost')
                    ->numeric(2),
                TextColumn::make('margin')
                    ->numeric(2)
                    ->sortable(),
                TextColumn::make('margin_percent')
                    ->label('Margin %')
                    ->formatStateUsing(fn ($state): string => number_format((float) $state, 2) . '%')
                    ->sortable()
                    ->color(fn ($state): string => (float) $state < 0 ? 'danger' : 'success'),
                TextColumn::make('anomaly_score')
                    ->label('Anomaly')
                    ->formatStateUsing(fn ($state): string => number_format((float) $state, 4)),
            ])
            ->filters([
                SelectFilter::make('low_margin')
                    ->label('Low Margin Only')
                    ->options(['1' => 'Yes'])
                    ->query(fn ($query, $data) => filled($data['value'])
                        ? $query->where('margin_percent', '<', 10)
                        : $query),
            ])
            ->actions([
                TableAction::make('recalculate')
                    ->label('Recalculate')
                    ->icon('heroicon-o-arrow-path')
                    ->action(function (ProfitabilitySnapshot $record) use ($companyId): void {
                        RecalculateSiteProfitabilityJob::dispatch((int) $companyId, $record->scope_ref);
                        Notification::make()
                            ->title('Recalculation queued for ' . $record->scope_ref)
                            ->success()
                            ->send();
                    }),
            ])
            ->defaultSort('margin_percent', 'asc');
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
