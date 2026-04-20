<?php

namespace Modules\ZeroPay\Filament\Resources;

use App\Filament\Resources\BaseTenantResource;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\ZeroPay\Filament\Resources\FollowupQueueResource\Pages\ListFollowupQueue;
use Modules\ZeroPay\Models\ZeroPayFollowup;

class FollowupQueueResource extends BaseTenantResource
{
    protected static ?string $model = ZeroPayFollowup::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationGroup = 'ZeroPay';
    protected static ?string $navigationLabel = 'Follow-up Queue';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->sortable(),
            Tables\Columns\TextColumn::make('session.public_token')->label('Session')->limit(16),
            Tables\Columns\TextColumn::make('channel')->badge(),
            Tables\Columns\TextColumn::make('status')->badge(),
            Tables\Columns\TextColumn::make('scheduled_at')->dateTime(),
            Tables\Columns\TextColumn::make('sent_at')->dateTime(),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
        ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFollowupQueue::route('/'),
        ];
    }
}
