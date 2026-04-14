<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages\CreateInvoice;
use App\Filament\Resources\InvoiceResource\Pages\EditInvoice;
use App\Filament\Resources\InvoiceResource\Pages\ListInvoices;
use App\Filament\Resources\InvoiceResource\RelationManagers\InvoiceItemsRelationManager;
use App\Filament\Resources\InvoiceResource\RelationManagers\InvoicePaymentsRelationManager;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class InvoiceResource extends BaseTenantResource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-currency-dollar';

    protected static ?string $navigationGroup = 'Finance';

    protected static ?string $navigationLabel = 'Invoices';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('client_id')->relationship('client', 'name')->searchable()->preload()->required(),
            Forms\Components\Select::make('project_id')->relationship('project', 'project_name')->searchable()->preload(),
            Forms\Components\DatePicker::make('issue_date')->required(),
            Forms\Components\DatePicker::make('due_date')->required(),
            Forms\Components\Select::make('status')->options([
                'unpaid' => 'Unpaid',
                'partial' => 'Partial',
                'paid' => 'Paid',
                'canceled' => 'Canceled',
                'draft' => 'Draft',
            ])->default('unpaid')->required(),
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
                        ->afterStateHydrated(fn (Set $set, Get $get) => $set('amount', ((float) ($get('quantity') ?: 0)) * ((float) ($get('unit_price') ?: 0))))
                        ->afterStateUpdated(fn (Set $set, Get $get) => $set('amount', ((float) ($get('quantity') ?: 0)) * ((float) ($get('unit_price') ?: 0)))),
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
                Tables\Columns\TextColumn::make('invoice_number')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('client.name')->label('Client')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('project.project_name')->label('Project')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('total')->money('USD')->sortable(),
                Tables\Columns\TextColumn::make('due_date')->date()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'unpaid' => 'Unpaid',
                    'partial' => 'Partial',
                    'paid' => 'Paid',
                    'canceled' => 'Canceled',
                    'draft' => 'Draft',
                ]),
                SelectFilter::make('client_id')->relationship('client', 'name')->label('Client')->searchable()->preload(),
                SelectFilter::make('project_id')->relationship('project', 'project_name')->label('Project')->searchable()->preload(),
                Filter::make('due_date')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['from'] ?? null, fn (Builder $q, $date) => $q->whereDate('invoices.due_date', '>=', $date))
                        ->when($data['until'] ?? null, fn (Builder $q, $date) => $q->whereDate('invoices.due_date', '<=', $date))),
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
            InvoicePaymentsRelationManager::class,
            InvoiceItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInvoices::route('/'),
            'create' => CreateInvoice::route('/create'),
            'edit' => EditInvoice::route('/{record}/edit'),
        ];
    }
}
