<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimeLogResource\Pages\ListTimeLogs;
use App\Filament\Resources\TimeLogResource\Pages\ViewTimeLog;
use App\Models\ProjectTimeLog;
use App\Providers\TitanPanelProvider;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TimeLogResource extends BaseTenantResource
{
    protected static ?string $model = ProjectTimeLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationGroup = 'Operations';

    protected static ?string $navigationLabel = 'Time Logs';

    protected static ?int $navigationSort = 2;

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

        return !in_array($user->permission('view_timelogs'), [false, null, 'none'], true);
    }

    public static function canView($record): bool
    {
        if (!static::canViewAny()) {
            return false;
        }

        $user = auth()->user();

        if (!$user) {
            return false;
        }

        if ((int) ($user->is_superadmin ?? 0) === 1) {
            return true;
        }

        if ((int) ($record->company_id ?? 0) !== (int) ($user->company_id ?? 0)) {
            return false;
        }

        $permission = $user->permission('view_timelogs');

        if ($permission === 'all') {
            return true;
        }

        if ($permission === 'added') {
            return (int) ($record->added_by ?? 0) === (int) $user->id;
        }

        if ($permission === 'owned') {
            return (int) $record->user_id === (int) $user->id;
        }

        if ($permission === 'both') {
            return (int) $record->user_id === (int) $user->id || (int) ($record->added_by ?? 0) === (int) $user->id;
        }

        return false;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Time Log')->schema([
                TextEntry::make('user.name')->label('Employee'),
                TextEntry::make('project.project_name')->label('Project'),
                TextEntry::make('task.heading')->label('Task'),
                TextEntry::make('start_time')->dateTime(),
                TextEntry::make('end_time')->dateTime(),
                TextEntry::make('total_minutes'),
                TextEntry::make('approved')->badge(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Employee')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('project.project_name')->label('Project')->searchable(),
                Tables\Columns\TextColumn::make('task.heading')->label('Task')->searchable(),
                Tables\Columns\TextColumn::make('start_time')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('end_time')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('hours_only')->label('Duration'),
                Tables\Columns\TextColumn::make('approved')->badge(),
            ])
            ->filters([
                SelectFilter::make('user_id')->relationship('user', 'name')->label('Employee')->searchable()->preload(),
                SelectFilter::make('approved')->options(['1' => 'Approved', '0' => 'Pending']),
                Filter::make('start_time')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from'),
                        \Filament\Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['from'] ?? null, fn (Builder $q, $date) => $q->whereDate('project_time_logs.start_time', '>=', $date))
                        ->when($data['until'] ?? null, fn (Builder $q, $date) => $q->whereDate('project_time_logs.start_time', '<=', $date))),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTimeLogs::route('/'),
            'view' => ViewTimeLog::route('/{record}'),
        ];
    }
}
