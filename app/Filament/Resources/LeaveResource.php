<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveResource\Pages\ListLeaves;
use App\Filament\Resources\LeaveResource\Pages\ViewLeave;
use App\Models\Leave;
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

class LeaveResource extends BaseTenantResource
{
    protected static ?string $model = Leave::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Operations';

    protected static ?string $navigationLabel = 'Leave';

    protected static ?int $navigationSort = 3;

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

        return !in_array($user->permission('view_leave'), [false, null, 'none'], true);
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

        $permission = $user->permission('view_leave');

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
            Section::make('Leave')->schema([
                TextEntry::make('user.name')->label('Employee'),
                TextEntry::make('type.type_name')->label('Type'),
                TextEntry::make('leave_date')->date(),
                TextEntry::make('duration'),
                TextEntry::make('status')->badge(),
                TextEntry::make('reason')->markdown(),
                TextEntry::make('approvedBy.name')->label('Approved By'),
                TextEntry::make('approved_at')->dateTime(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Employee')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('type.type_name')->label('Type')->searchable(),
                Tables\Columns\TextColumn::make('leave_date')->date()->sortable(),
                Tables\Columns\TextColumn::make('duration')->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('approvedBy.name')->label('Approved By')->searchable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                    'canceled' => 'Canceled',
                ]),
                SelectFilter::make('user_id')->relationship('user', 'name')->label('Employee')->searchable()->preload(),
                Filter::make('leave_date')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from'),
                        \Filament\Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['from'] ?? null, fn (Builder $q, $date) => $q->whereDate('leaves.leave_date', '>=', $date))
                        ->when($data['until'] ?? null, fn (Builder $q, $date) => $q->whereDate('leaves.leave_date', '<=', $date))),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLeaves::route('/'),
            'view' => ViewLeave::route('/{record}'),
        ];
    }
}
