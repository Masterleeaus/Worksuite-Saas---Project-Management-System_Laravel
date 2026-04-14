<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages\CreateClient;
use App\Filament\Resources\ClientResource\Pages\EditClient;
use App\Filament\Resources\ClientResource\Pages\ListClients;
use App\Filament\Resources\ClientResource\Pages\ViewClient;
use App\Models\User;
use App\Providers\TitanPanelProvider;
use App\Scopes\ActiveScope;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ClientResource extends BaseTenantResource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'CRM';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Clients';

    public static function canAccess(): bool
    {
        return TitanPanelProvider::canAccess();
    }

    public static function canViewAny(): bool
    {
        if (!static::canAccess()) {
            return false;
        }

        $user = auth()->user();

        if (!$user) {
            return false;
        }

        if (method_exists($user, 'hasRole') && ($user->hasRole('superadmin') || $user->hasRole('admin'))) {
            return true;
        }

        return !in_array($user->permission('view_clients'), [false, null, 'none'], true);
    }

    public static function canView($record): bool
    {
        return static::canAccessRecord($record, 'view_clients');
    }

    public static function canEdit($record): bool
    {
        return static::canAccessRecord($record, 'edit_clients');
    }

    public static function canCreate(): bool
    {
        if (!static::canAccess()) {
            return false;
        }

        $user = auth()->user();

        if (!$user) {
            return false;
        }

        if (method_exists($user, 'hasRole') && ($user->hasRole('superadmin') || $user->hasRole('admin'))) {
            return true;
        }

        return in_array($user->permission('add_clients'), User::ALL_ADDED_BOTH, true);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScope(ActiveScope::class)
            ->whereNull('users.is_client_contact')
            ->whereHas('roles', fn (Builder $query) => $query->where('name', 'client'))
            ->with(['clientDetails', 'clientDetails.addedBy']);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(191),
            Forms\Components\TextInput::make('company_name')
                ->label('Company Name')
                ->maxLength(191),
            Forms\Components\TextInput::make('email')
                ->email()
                ->maxLength(191),
            Forms\Components\TextInput::make('mobile')
                ->label('Phone')
                ->maxLength(30),
            Forms\Components\Select::make('status')
                ->options([
                    'active' => 'Active',
                    'deactive' => 'Inactive',
                    'inactive' => 'Inactive',
                ])
                ->default('active')
                ->required(),
            Forms\Components\TextInput::make('password')
                ->password()
                ->maxLength(191)
                ->required(fn (string $operation): bool => $operation === 'create')
                ->dehydrated(fn (?string $state): bool => filled($state))
                ->helperText('Only required when creating a client.'),
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Client')
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('clientDetails.company_name')->label('Company Name'),
                        TextEntry::make('email'),
                        TextEntry::make('mobile')->label('Phone'),
                        TextEntry::make('status'),
                        TextEntry::make('created_at')->dateTime(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('clientDetails.company_name')
                    ->label('Company Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mobile')
                    ->label('Phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->sortable()
                    ->colors([
                        'success' => 'active',
                        'danger' => ['deactive', 'inactive'],
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'deactive' => 'Inactive',
                        'inactive' => 'Inactive',
                    ]),
                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'] ?? null, fn (Builder $query, $date) => $query->whereDate('users.created_at', '>=', $date))
                            ->when($data['created_until'] ?? null, fn (Builder $query, $date) => $query->whereDate('users.created_at', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListClients::route('/'),
            'create' => CreateClient::route('/create'),
            'view' => ViewClient::route('/{record}'),
            'edit' => EditClient::route('/{record}/edit'),
        ];
    }

    protected static function canAccessRecord(User $record, string $permissionName): bool
    {
        if (!static::canAccess()) {
            return false;
        }

        $user = auth()->user();

        if (!$user) {
            return false;
        }

        if (method_exists($user, 'hasRole') && ($user->hasRole('superadmin') || $user->hasRole('admin'))) {
            return true;
        }

        $permission = $user->permission($permissionName);

        if ($permission === 'all') {
            return true;
        }

        if (in_array($permission, ['added', 'both'], true)) {
            return (int) optional($record->clientDetails)->added_by === (int) $user->id;
        }

        return false;
    }
}
