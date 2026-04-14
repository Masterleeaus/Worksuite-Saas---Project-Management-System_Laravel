<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectFileResource\Pages\ListProjectFiles;
use App\Filament\Resources\ProjectFileResource\Pages\ViewProjectFile;
use App\Models\ProjectFile;
use App\Providers\TitanPanelProvider;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProjectFileResource extends BaseTenantResource
{
    protected static ?string $model = ProjectFile::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected static ?string $navigationGroup = 'Documents';

    protected static ?string $navigationLabel = 'Project Files';

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

        return !in_array($user->permission('view_project_files'), [false, null, 'none'], true);
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

        $permission = $user->permission('view_project_files');

        if ($permission === 'all') {
            return true;
        }

        if (in_array($permission, ['added', 'owned', 'both'], true)) {
            return (int) ($record->added_by ?? 0) === (int) $user->id || (int) ($record->user_id ?? 0) === (int) $user->id;
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
                Tables\Columns\TextColumn::make('filename')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('project.project_name')->label('Project')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('description')->limit(50),
                Tables\Columns\TextColumn::make('size')->label('Size')->sortable(),
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
            Section::make('Document')->schema([
                TextEntry::make('filename'),
                TextEntry::make('project.project_name')->label('Project'),
                TextEntry::make('description')->markdown(),
                TextEntry::make('file_url')->url(fn ($state) => $state, true),
                TextEntry::make('created_at')->dateTime(),
            ])->columns(2),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProjectFiles::route('/'),
            'view' => ViewProjectFile::route('/{record}'),
        ];
    }
}
