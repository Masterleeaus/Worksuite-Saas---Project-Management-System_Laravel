<?php

namespace Modules\Accountings\Filament\Pages;

use App\Models\Invoice;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Modules\Accountings\Actions\AdjustInvoiceAction;
use Modules\Accountings\Entities\FinancialTransaction;

class AdjustmentReviewQueuePage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Adjustment Review Queue';
    protected static ?string $navigationIcon  = 'heroicon-o-clipboard-document-list';
    protected static string $view             = 'accountings::filament.pages.adjustment-review-queue';

    public function table(Table $table): Table
    {
        $companyId = auth()->user()?->company_id;

        return $table
            ->query(
                FinancialTransaction::query()
                    ->where('company_id', $companyId)
                    ->whereIn('transaction_type', ['adjustment', 'writeoff', 'credit_note'])
                    ->where('status', 'pending_review')
            )
            ->columns([
                TextColumn::make('reference')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('transaction_type')
                    ->label('Type')
                    ->badge(),
                TextColumn::make('amount')
                    ->formatStateUsing(fn ($state): string => number_format((float) $state, 2))
                    ->sortable(),
                TextColumn::make('site_ref')
                    ->label('Site'),
                TextColumn::make('contract_ref')
                    ->label('Contract'),
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending_review',
                        'success' => 'approved',
                        'danger'  => 'rejected',
                    ]),
                TextColumn::make('occurred_at')
                    ->label('Transaction Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('transaction_type')
                    ->options([
                        'adjustment'  => 'Adjustment',
                        'writeoff'    => 'Write-off',
                        'credit_note' => 'Credit Note',
                    ]),
            ])
            ->actions([
                TableAction::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (FinancialTransaction $record): void {
                        $record->status = 'approved';
                        $record->save();

                        Notification::make()
                            ->title('Transaction approved')
                            ->success()
                            ->send();
                    }),

                TableAction::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (FinancialTransaction $record): void {
                        $record->status = 'rejected';
                        $record->save();

                        Notification::make()
                            ->title('Transaction rejected')
                            ->danger()
                            ->send();
                    }),
            ])
            ->defaultSort('occurred_at', 'desc');
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
