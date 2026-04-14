<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages\CreateTask;
use App\Filament\Resources\TaskResource\Pages\EditTask;
use App\Filament\Resources\TaskResource\Pages\ListTasks;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TaskResource extends BaseTenantResource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';

    protected static ?string $navigationGroup = 'Projects';

    protected static ?string $navigationLabel = 'Tasks';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('heading')->required()->maxLength(191),
            Forms\Components\Select::make('project_id')->relationship('project', 'project_name')->searchable()->preload(),
            Forms\Components\Select::make('task_category_id')->relationship('category', 'category_name')->searchable()->preload(),
            Forms\Components\Select::make('board_column_id')->relationship('boardColumn', 'column_name')->searchable()->preload(),
            Forms\Components\Select::make('priority')->options([
                'low' => 'Low',
                'medium' => 'Medium',
                'high' => 'High',
            ])->default('medium')->required(),
            Forms\Components\Select::make('status')->options([
                'incomplete' => 'Incomplete',
                'completed' => 'Completed',
            ])->default('incomplete')->required(),
            Forms\Components\DatePicker::make('start_date'),
            Forms\Components\DatePicker::make('due_date'),
            Forms\Components\TextInput::make('estimate_hours')->numeric()->default(0),
            Forms\Components\TextInput::make('estimate_minutes')->numeric()->default(0),
            Forms\Components\Textarea::make('description')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => static::applyTenantScope($query))
            ->columns([
                Tables\Columns\TextColumn::make('heading')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('project.project_name')->label('Project')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('project.client.name')->label('Client')->searchable(),
                Tables\Columns\TextColumn::make('priority')->badge()->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('start_date')->date()->sortable(),
                Tables\Columns\TextColumn::make('due_date')->date()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'incomplete' => 'Incomplete',
                    'completed' => 'Completed',
                ]),
                SelectFilter::make('project_id')->relationship('project', 'project_name')->label('Project')->searchable()->preload(),
                SelectFilter::make('client_id')
                    ->label('Client')
                    ->options(fn (): array => \App\Models\User::query()->orderBy('name')->pluck('name', 'id')->all())
                    ->query(fn (Builder $query, array $data): Builder => $query->when($data['value'] ?? null, fn (Builder $q, $clientId) => $q->whereHas('project', fn (Builder $projectQuery) => $projectQuery->where('client_id', $clientId)))),
                Filter::make('due_date')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['from'] ?? null, fn (Builder $q, $date) => $q->whereDate('tasks.due_date', '>=', $date))
                        ->when($data['until'] ?? null, fn (Builder $q, $date) => $q->whereDate('tasks.due_date', '<=', $date))),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
                ...(class_exists(\Filament\Tables\Actions\ExportBulkAction::class)
                    ? [\Filament\Tables\Actions\ExportBulkAction::make()]
                    : []),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTasks::route('/'),
            'create' => CreateTask::route('/create'),
            'edit' => EditTask::route('/{record}/edit'),
        ];
    }
}
