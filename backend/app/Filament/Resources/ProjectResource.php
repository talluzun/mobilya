<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?string $navigationLabel = 'Projeler';
    protected static ?string $modelLabel = 'Proje';
    protected static ?string $pluralModelLabel = 'Projeler';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('title')
                ->label('Başlık')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn (?string $state, callable $set) => $state ? $set('slug', Str::slug($state)) : null),
            TextInput::make('slug')->label('Slug')->required()->unique(ignoreRecord: true),
            RichEditor::make('description')->label('Açıklama')->columnSpanFull(),
            FileUpload::make('cover_image')->label('Kapak Görseli')->image()->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])->disk('public')->directory('projects/covers'),
            Repeater::make('media')
                ->label('Galeri')
                ->relationship()
                ->schema([
                    FileUpload::make('path')->label('Görsel')->image()->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])->disk('public')->directory('projects/gallery')->required(),
                ])
                ->orderColumn('sort_order')
                ->reorderable()
                ->columnSpanFull(),
            TextInput::make('city')->label('Şehir'),
            TextInput::make('venue_type')->label('Mekan Tipi'),
            Select::make('products')
                ->label('Kullanılan Ürünler')
                ->relationship('products', 'name')
                ->multiple()
                ->preload()
                ->searchable(),
            TextInput::make('sort_order')->label('Sıralama')->numeric()->default(0),
            Toggle::make('is_active')->label('Aktif')->default(true),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Başlık')->searchable()->sortable(),
                TextColumn::make('city')->label('Şehir')->sortable(),
                TextColumn::make('venue_type')->label('Mekan Tipi')->sortable(),
                TextColumn::make('sort_order')->label('Sıra')->sortable(),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'view' => Pages\ViewProject::route('/{record}'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
