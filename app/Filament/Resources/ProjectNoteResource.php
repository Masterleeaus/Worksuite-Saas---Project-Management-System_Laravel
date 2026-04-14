<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectNoteResource\Pages\ListProjectNotes;
use App\Filament\Resources\ProjectNoteResource\Pages\ViewProjectNote;
use App\Models\ProjectNote;
use App\Providers\TitanPanelProvider;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProjectNoteResource extends BaseTenantResource
{
    protected static ?string $model = ProjectNote::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Activity';

    protected static ?string $navigationLabel = 'Project Notes';

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

        return !in_array($user->permission('view_project_note'), [false, null, 'none'], true);
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

        if ((int) optional($record->project)->company_id !== (int) ($user->company_id ?? 0)) {
            return false;
        }

        $permission = $user->permission('view_project_note');

        if ($permission === 'all') {
            return true;
        }

        if ($permission === 'added') {
            return (int) ($record->added_by ?? 0) === (int) $user->id;
        }

        if ($permission === 'owned') {
            return (int) ($record->client_id ?? 0) === (int) $user->id;
        }

        if ($permission === 'both') {
            return (int) ($record->added_by ?? 0) === (int) $user->id || (int) ($record->client_id ?? 0) === (int) $user->id;
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('project.project_name')->label('Project')->searchable(),
                Tables\Columns\IconColumn::make('is_client_show')->label('Client Visible')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('project_id')->relationship('project', 'project_name')->label('Project')->searchable()->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Note')->schema([
                TextEntry::make('title'),
                TextEntry::make('project.project_name')->label('Project'),
                TextEntry::make('details')->markdown()->columnSpanFull(),
                TextEntry::make('created_at')->dateTime(),
            ])->columns(2),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProjectNotes::route('/'),
            'view' => ViewProjectNote::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['project']);
        $companyId = static::currentCompanyId();

        if ($companyId) {
            $query->whereHas('project', fn (Builder $q) => $q->where('projects.company_id', $companyId));
        }

        return $query;
    }
}
