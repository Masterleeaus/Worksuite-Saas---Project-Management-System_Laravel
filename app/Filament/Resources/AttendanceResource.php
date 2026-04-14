<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages\ListAttendances;
use App\Filament\Resources\AttendanceResource\Pages\ViewAttendance;
use App\Models\Attendance;
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

class AttendanceResource extends BaseTenantResource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-finger-print';

    protected static ?string $navigationGroup = 'Operations';

    protected static ?string $navigationLabel = 'Attendance';

    protected static ?int $navigationSort = 1;

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

        return !in_array($user->permission('view_attendance'), [false, null, 'none'], true);
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

        $permission = $user->permission('view_attendance');

        if ($permission === 'all') {
            return true;
        }

        if ($permission === 'owned') {
            return (int) $record->user_id === (int) $user->id;
        }

        if ($permission === 'added') {
            return (int) ($record->added_by ?? 0) === (int) $user->id;
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
            Section::make('Attendance')->schema([
                TextEntry::make('user.name')->label('Employee'),
                TextEntry::make('clock_in_time')->dateTime(),
                TextEntry::make('clock_out_time')->dateTime(),
                TextEntry::make('date')->date(),
                TextEntry::make('working_from'),
                TextEntry::make('work_from_type'),
                TextEntry::make('late'),
                TextEntry::make('half_day')->label('Half Day'),
                TextEntry::make('location.location')->label('Location'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Employee')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('date')->date()->sortable(),
                Tables\Columns\TextColumn::make('clock_in_time')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('clock_out_time')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('working_from')->searchable(),
                Tables\Columns\TextColumn::make('late')->badge(),
                Tables\Columns\TextColumn::make('half_day')->badge(),
            ])
            ->filters([
                SelectFilter::make('user_id')->relationship('user', 'name')->label('Employee')->searchable()->preload(),
                SelectFilter::make('late')->options(['yes' => 'Yes', 'no' => 'No']),
                Filter::make('date')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from'),
                        \Filament\Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['from'] ?? null, fn (Builder $q, $date) => $q->whereDate('attendances.date', '>=', $date))
                        ->when($data['until'] ?? null, fn (Builder $q, $date) => $q->whereDate('attendances.date', '<=', $date))),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAttendances::route('/'),
            'view' => ViewAttendance::route('/{record}'),
        ];
    }
}
