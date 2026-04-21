<?php

namespace Modules\Accountings\Filament\Resources;

use App\Filament\Resources\BaseTenantResource;
use App\Providers\TitanPanelProvider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Accountings\Entities\Accounting;

class AccountsResource extends BaseTenantResource
{
    protected static ?string $model = Accounting::class;
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Accounts';
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function canAccess(): bool
    {
        return TitanPanelProvider::canAccess();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('acc_code')->label('Code')->required(),
            Forms\Components\TextInput::make('acc_name')->label('Name')->required(),
            Forms\Components\TextInput::make('acc_type')->label('Type')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('acc_code')->label('Code')->searchable(),
            Tables\Columns\TextColumn::make('acc_name')->label('Name')->searchable(),
            Tables\Columns\TextColumn::make('acc_type')->label('Type')->badge(),
        ])->actions([
            Tables\Actions\EditAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => AccountsResource\Pages\ListAccounts::route('/'),
            'create' => AccountsResource\Pages\CreateAccount::route('/create'),
            'edit' => AccountsResource\Pages\EditAccount::route('/{record}/edit'),
        ];
    }
}
