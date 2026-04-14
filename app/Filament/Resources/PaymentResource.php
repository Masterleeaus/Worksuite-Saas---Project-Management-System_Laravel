<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages\CreatePayment;
use App\Filament\Resources\PaymentResource\Pages\EditPayment;
use App\Filament\Resources\PaymentResource\Pages\ListPayments;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PaymentResource extends BaseTenantResource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Finance';

    protected static ?string $navigationLabel = 'Payments';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('invoice_id')->relationship('invoice', 'invoice_number')->searchable()->preload(),
            Forms\Components\Select::make('project_id')->relationship('project', 'project_name')->searchable()->preload(),
            Forms\Components\Select::make('currency_id')->relationship('currency', 'currency_code')->searchable()->preload(),
            Forms\Components\TextInput::make('amount')->numeric()->prefix('$')->required(),
            Forms\Components\Select::make('status')->options([
                'pending' => 'Pending',
                'complete' => 'Complete',
                'failed' => 'Failed',
            ])->default('pending')->required(),
            Forms\Components\DateTimePicker::make('paid_on'),
            Forms\Components\TextInput::make('gateway')->maxLength(191),
            Forms\Components\TextInput::make('transaction_id')->maxLength(191),
            Forms\Components\Textarea::make('remarks')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => static::applyTenantScope($query))
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('invoice.invoice_number')->label('Invoice')->searchable(),
                Tables\Columns\TextColumn::make('project.project_name')->label('Project')->searchable(),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('amount')->money('USD')->sortable(),
                Tables\Columns\TextColumn::make('paid_on')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'complete' => 'Complete',
                    'failed' => 'Failed',
                ]),
                SelectFilter::make('project_id')->relationship('project', 'project_name')->label('Project')->searchable()->preload(),
                SelectFilter::make('invoice.client_id')
                    ->relationship('invoice.client', 'name')
                    ->label('Client')
                    ->searchable()
                    ->preload(),
                Filter::make('paid_on')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['from'] ?? null, fn (Builder $q, $date) => $q->whereDate('payments.paid_on', '>=', $date))
                        ->when($data['until'] ?? null, fn (Builder $q, $date) => $q->whereDate('payments.paid_on', '<=', $date))),
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
            'index' => ListPayments::route('/'),
            'create' => CreatePayment::route('/create'),
            'edit' => EditPayment::route('/{record}/edit'),
        ];
    }
}
