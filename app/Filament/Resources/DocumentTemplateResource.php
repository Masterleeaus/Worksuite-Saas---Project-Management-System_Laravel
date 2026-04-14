<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentTemplateResource\Pages\ManageDocumentTemplates;
use App\Providers\TitanPanelProvider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\TitanDocs\Entities\DocumentTemplate;

class DocumentTemplateResource extends BaseTenantResource
{
    protected static ?string $model = DocumentTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $navigationGroup = 'TitanDocs';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Document Templates';

    public static function canAccess(): bool
    {
        return TitanPanelProvider::canAccess();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('template_type')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('document_type')
                ->required()
                ->maxLength(255),
            Forms\Components\Textarea::make('html_content')
                ->required()
                ->rows(8)
                ->columnSpanFull(),
            Forms\Components\Toggle::make('is_active')
                ->default(true),
            Forms\Components\Toggle::make('is_global')
                ->default(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('template_type')
                    ->badge(),
                Tables\Columns\TextColumn::make('document_type')
                    ->badge(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageDocumentTemplates::route('/'),
        ];
    }
}
