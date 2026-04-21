<?php

namespace Modules\Accountings\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Modules\Accountings\Actions\CloseAccountingPeriodAction;
use Modules\Accountings\Entities\AccountingPeriod;

class PeriodClosePage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Period Close';
    protected static ?string $navigationIcon  = 'heroicon-o-lock-closed';
    protected static string $view             = 'accountings::filament.pages.period-close';

    public function table(Table $table): Table
    {
        $companyId = auth()->user()?->company_id;

        return $table
            ->query(AccountingPeriod::query()->where('company_id', $companyId))
            ->columns([
                TextColumn::make('period_key')
                    ->label('Period')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('period_start')
                    ->label('Start')
                    ->date()
                    ->sortable(),
                TextColumn::make('period_end')
                    ->label('End')
                    ->date(),
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'closed',
                        'info'    => 'open',
                    ]),
                TextColumn::make('closed_at')
                    ->label('Closed At')
                    ->dateTime(),
                TextColumn::make('closedBy.name')
                    ->label('Closed By'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'open'   => 'Open',
                        'closed' => 'Closed',
                    ]),
            ])
            ->actions([
                TableAction::make('close')
                    ->label('Close Period')
                    ->icon('heroicon-o-lock-closed')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalDescription('Closing a period is irreversible. Confirm only when all transactions are reconciled.')
                    ->visible(fn (AccountingPeriod $record): bool => $record->status !== 'closed')
                    ->action(function (AccountingPeriod $record) use ($companyId): void {
                        app(CloseAccountingPeriodAction::class)->execute(
                            (int) $companyId,
                            $record->period_key,
                            $record->period_start->toDateString(),
                            $record->period_end->toDateString(),
                        );

                        Notification::make()
                            ->title('Period ' . $record->period_key . ' closed')
                            ->success()
                            ->send();
                    }),
            ])
            ->defaultSort('period_start', 'desc');
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
