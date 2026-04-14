<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages\ListEmployees;
use App\Filament\Resources\EmployeeResource\Pages\ViewEmployee;
use App\Filament\Resources\EmployeeResource\RelationManagers\EmployeeAttendanceRelationManager;
use App\Filament\Resources\EmployeeResource\RelationManagers\EmployeeDocumentsRelationManager;
use App\Filament\Resources\EmployeeResource\RelationManagers\EmployeeLeavesRelationManager;
use App\Filament\Resources\EmployeeResource\RelationManagers\EmployeeProjectsRelationManager;
use App\Filament\Resources\EmployeeResource\RelationManagers\EmployeeTasksRelationManager;
use App\Filament\Resources\EmployeeResource\RelationManagers\EmployeeTimeLogsRelationManager;
use App\Models\User;
use App\Providers\TitanPanelProvider;
use App\Scopes\ActiveScope;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EmployeeResource extends BaseTenantResource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Team';

    protected static ?string $navigationLabel = 'Employees';

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

        return !in_array($user->permission('view_employees'), [false, null, 'none'], true);
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

        if ((int) ($record->company_id ?? 0) !== (int) ($user->company_id ?? 0) && (int) ($user->is_superadmin ?? 0) !== 1) {
            return false;
        }

        $permission = $user->permission('view_employees');

        if ($permission === 'all') {
            return true;
        }

        if ($permission === 'owned') {
            return (int) $user->id === (int) $record->id;
        }

        if ($permission === 'added') {
            return (int) ($record->added_by ?? 0) === (int) $user->id;
        }

        if ($permission === 'both') {
            return (int) $user->id === (int) $record->id || (int) ($record->added_by ?? 0) === (int) $user->id;
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
            Section::make('Employee')->schema([
                TextEntry::make('name'),
                TextEntry::make('email'),
                TextEntry::make('employeeDetail.employee_id')->label('Employee ID'),
                TextEntry::make('employeeDetail.designation.name')->label('Designation'),
                TextEntry::make('employeeDetail.department.team_name')->label('Department'),
                TextEntry::make('employeeDetail.joining_date')->label('Joining Date')->date(),
                TextEntry::make('status'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('employeeDetail.employee_id')->label('Employee ID')->searchable(),
                Tables\Columns\TextColumn::make('employeeDetail.designation.name')->label('Designation')->searchable(),
                Tables\Columns\TextColumn::make('employeeDetail.department.team_name')->label('Department')->searchable(),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'active' => 'Active',
                    'deactive' => 'Inactive',
                ]),
                SelectFilter::make('employeeDetail.department_id')
                    ->label('Department')
                    ->relationship('employeeDetail.department', 'team_name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            EmployeeProjectsRelationManager::class,
            EmployeeTasksRelationManager::class,
            EmployeeAttendanceRelationManager::class,
            EmployeeTimeLogsRelationManager::class,
            EmployeeLeavesRelationManager::class,
            EmployeeDocumentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEmployees::route('/'),
            'view' => ViewEmployee::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScope(ActiveScope::class)
            ->whereHas('roles', fn (Builder $query) => $query->where('name', 'employee'))
            ->whereHas('employeeDetail')
            ->with(['employeeDetail.designation', 'employeeDetail.department']);
    }
}
