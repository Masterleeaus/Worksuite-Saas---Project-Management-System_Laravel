<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages\CreateProject;
use App\Filament\Resources\ProjectResource\Pages\EditProject;
use App\Filament\Resources\ProjectResource\Pages\ListProjects;
use App\Filament\Resources\ProjectResource\Pages\ViewProject;
use App\Models\Project;
use App\Models\ProjectStatusSetting;
use App\Models\User;
use App\Providers\TitanPanelProvider;
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

class ProjectResource extends BaseTenantResource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Operations';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Projects';

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

        return !in_array($user->permission('view_projects'), [false, null, 'none'], true);
    }

    public static function canView($record): bool
    {
        return static::canAccessRecord($record, 'view_projects', true);
    }

    public static function canEdit($record): bool
    {
        return static::canAccessRecord($record, 'edit_projects', false);
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

        return in_array($user->permission('add_projects'), ['all', 'added'], true);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['client.clientDetails', 'projectAdmin', 'members.user'])
            ->withCount(['tasks', 'invoices']);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('project_name')
                ->required()
                ->maxLength(191),
            Forms\Components\Select::make('client_id')
                ->label('Client')
                ->options(static::clientOptions())
                ->searchable(),
            Forms\Components\Select::make('status')
                ->options(static::projectStatusOptions())
                ->default('in progress')
                ->required(),
            Forms\Components\TextInput::make('completion_percent')
                ->label('Progress')
                ->numeric()
                ->minValue(0)
                ->maxValue(100)
                ->default(0),
            Forms\Components\DatePicker::make('start_date')
                ->required(),
            Forms\Components\DatePicker::make('deadline'),
            Forms\Components\Select::make('project_admin')
                ->label('Project Admin')
                ->options(static::employeeOptions())
                ->searchable(),
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Project')
                    ->schema([
                        TextEntry::make('project_name'),
                        TextEntry::make('client.name')->label('Client'),
                        TextEntry::make('status'),
                        TextEntry::make('completion_percent')->label('Progress')->suffix('%'),
                        TextEntry::make('start_date')->date(),
                        TextEntry::make('deadline')->date(),
                        TextEntry::make('projectAdmin.name')->label('Project Admin'),
                        TextEntry::make('created_at')->dateTime(),
                    ])->columns(2),
                Section::make('Relationships')
                    ->schema([
                        TextEntry::make('members_summary')
                            ->label('Members')
                            ->state(fn (Project $record): string => $record->members->pluck('user.name')->filter()->implode(', ')),
                        TextEntry::make('tasks_count')
                            ->label('Tasks')
                            ->state(fn (Project $record): int => $record->tasks_count ?? $record->tasks()->count()),
                        TextEntry::make('invoices_count')
                            ->label('Invoices')
                            ->state(fn (Project $record): int => $record->invoices_count ?? $record->invoices()->count()),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('project_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('completion_percent')
                    ->label('Progress')
                    ->suffix('%')
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deadline')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('projectAdmin.name')
                    ->label('Project Admin')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(static::projectStatusOptions()),
                Filter::make('deadline')
                    ->form([
                        Forms\Components\DatePicker::make('deadline_from'),
                        Forms\Components\DatePicker::make('deadline_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['deadline_from'] ?? null, fn (Builder $query, $date) => $query->whereDate('projects.deadline', '>=', $date))
                            ->when($data['deadline_until'] ?? null, fn (Builder $query, $date) => $query->whereDate('projects.deadline', '<=', $date));
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
            'index' => ListProjects::route('/'),
            'create' => CreateProject::route('/create'),
            'view' => ViewProject::route('/{record}'),
            'edit' => EditProject::route('/{record}/edit'),
        ];
    }

    /**
     * @return array<int|string, string>
     */
    protected static function projectStatusOptions(): array
    {
        return ProjectStatusSetting::query()
            ->where('status', 'active')
            ->orderBy('status_name')
            ->pluck('status_name', 'status_name')
            ->all();
    }

    /**
     * @return array<int|string, string>
     */
    protected static function clientOptions(): array
    {
        return User::query()
            ->withoutGlobalScope(\App\Scopes\ActiveScope::class)
            ->whereNull('users.is_client_contact')
            ->whereHas('roles', fn (Builder $query) => $query->where('name', 'client'))
            ->orderBy('users.name')
            ->pluck('users.name', 'users.id')
            ->all();
    }

    /**
     * @return array<int|string, string>
     */
    protected static function employeeOptions(): array
    {
        return User::query()
            ->whereHas('roles', fn (Builder $query) => $query->where('name', 'employee'))
            ->orderBy('users.name')
            ->pluck('users.name', 'users.id')
            ->all();
    }

    protected static function canAccessRecord(Project $record, string $permissionName, bool $allowNoneMention): bool
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
        $userId = (int) $user->id;
        $memberIds = $record->members->pluck('user_id')->map(fn ($id) => (int) $id)->all();
        $isClient = method_exists($user, 'hasRole')
            ? $user->hasRole('client')
            : in_array('client', user_roles(), true);
        $isEmployee = method_exists($user, 'hasRole')
            ? $user->hasRole('employee')
            : in_array('employee', user_roles(), true);

        if ($permission === 'all') {
            return true;
        }

        if ($permission === 'added') {
            return (int) $record->added_by === $userId;
        }

        if ($permission === 'owned') {
            return ($isClient && (int) $record->client_id === $userId)
                || ($isEmployee && in_array($userId, $memberIds, true));
        }

        if ($permission === 'both') {
            return (int) $record->added_by === $userId
                || ($isClient && (int) $record->client_id === $userId)
                || ($isEmployee && in_array($userId, $memberIds, true));
        }

        if ($allowNoneMention && $permission === 'none') {
            return $record->mentionProject()->where('user_id', $userId)->exists();
        }

        return false;
    }
}
