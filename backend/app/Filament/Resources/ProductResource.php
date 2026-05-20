<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Support\Str;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;


class ProductResource extends \Filament\Resources\Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationLabel = 'Ürünler';
    protected static ?string $modelLabel = 'Ürün';
    protected static ?string $pluralModelLabel = 'Ürünler';

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['slug']) && ! empty($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return $data;
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        if (empty($data['slug']) && ! empty($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return $data;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Temel Bilgiler')
                        ->schema([
                            TextInput::make('name')
                                ->label('Ürün Adı')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (?string $state, callable $set): void {
                                    if (! $state) {
                                        return;
                                    }

                                    $set('slug', Str::slug($state));
                                }),
                            Hidden::make('slug'),
                            Select::make('category')
                                ->label('Kategori')
                                ->options([
                                    'sofa' => 'Sofa',
                                    'chair' => 'Chair',
                                    'table' => 'Table',
                                    'bed' => 'Bed',
                                    'storage' => 'Storage',
                                    'custom' => 'Custom',
                                ])
                                ->required(),
                            TextInput::make('room_type')
                                ->label('Mekan Tipi')
                                ->placeholder('Restoran, otel, ofis...'),
                            TextInput::make('material')
                                ->label('Malzeme')
                                ->placeholder('Ahşap, metal, kumaş...'),
                            Toggle::make('is_active')
                                ->label('Aktif')
                                ->default(true),
                        ])
                        ->columns(2),
                    Step::make('Ürün Galerisi')
                        ->schema([
                            Repeater::make('media')
                                ->relationship()
                                ->schema([
                                    FileUpload::make('path')
                                        ->label('Resim')
                                        ->image()
                                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                        ->disk('public')
                                        ->directory('products/gallery')
                                        ->visibility('public')
                                        ->required(),
                                ])
                                ->orderColumn('sort_order')
                                ->reorderable()
                                ->addActionLabel('Resim ekle')
                                ->columnSpanFull(),
                        ]),
                    Step::make('Küçük Resim')
                        ->schema([
                            FileUpload::make('thumbnail_path')
                                ->label('Küçük Resim')
                                ->image()
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->disk('public')
                                ->directory('products/thumbnails')
                                ->visibility('public'),
                        ]),
                    Step::make('Açıklama')
                        ->schema([
                            RichEditor::make('description')
                                ->columnSpanFull(),
                        ]),
                    Step::make('Detaylar')
                        ->schema([
                            Textarea::make('features')
                                ->label('Ürün Özellikleri')
                                ->helperText('Her satıra bir özellik yazın'),
                            Textarea::make('care_instructions')
                                ->label('Bakım Talimatları'),
                            Textarea::make('warranty_info')
                                ->label('Garanti Bilgisi'),
                        ]),
                    Step::make('Temel Fiyat')
                        ->schema([
                            TextInput::make('base_price')
                                ->label('Temel Fiyat')
                                ->numeric()
                                ->rules(['nullable', 'decimal:0,2'])
                                ->minValue(0),
                            TextInput::make('shipping_cost')
                                ->label('Kargo Bedeli')
                                ->numeric()
                                ->rules(['nullable', 'decimal:0,2'])
                                ->minValue(0),
                        ]),
                    Step::make('Teslim Süresi')
                        ->schema([
                            TextInput::make('delivery_time')
                                ->label('Teslim Süresi')
                                ->placeholder('4-6 hafta'),
                        ]),
                    Step::make('Renkler')
                        ->schema([
                            Group::make()
                                ->relationship('colorOption')
                                ->schema([
                                    Hidden::make('label')
                                        ->default('Color'),
                                    Hidden::make('key')
                                        ->default('color'),
                                    Hidden::make('type')
                                        ->default('swatch'),
                                    Hidden::make('is_required')
                                        ->default(true),
                                    Repeater::make('values')
                                        ->relationship()
                                        ->schema([
                                            Grid::make(12)
                                                ->schema([
                                                    TextInput::make('label')
                                                        ->required()
                                                        ->columnSpan(3),
                                                    ColorPicker::make('color_hex')
                                                        ->label('Renk')
                                                        ->afterStateHydrated(function ($state, callable $set): void {
                                                            $set('color_hex', self::normalizeHex($state));
                                                        })
                                                        ->dehydrateStateUsing(function ($state): ?string {
                                                            return self::normalizeHex($state);
                                                        })
                                                        ->rules(['required', 'regex:/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/'])
                                                        ->columnSpan(5),
                                                    TextInput::make('price_delta')
                                                        ->numeric()
                                                        ->rules(['nullable', 'decimal:0,2'])
                                                        ->label('Fiyat farkı')
                                                        ->helperText('İsteğe bağlı')
                                                        ->columnSpan(2),
                                                    Toggle::make('is_default')
                                                        ->label('Default')
                                                        ->columnSpan(2),
                                                ])
                                                ->columnSpanFull(),
                                        ])
                                        ->addActionLabel('Renk ekle')
                                        ->columns(4),
                                ]),
                        ]),
                    Step::make('Ek Özellikler')
                        ->schema([
                            Repeater::make('extraOptions')
                                ->relationship('extraOptions')
                                ->schema([
                                    TextInput::make('label')
                                        ->label('Seçenek')
                                        ->required(),
                                    Select::make('type')
                                        ->label('Tür')
                                        ->options([
                                            'button_group' => 'Düğme Grubu',
                                            'select' => 'Seçme',
                                            'radio' => 'Radyo',
                                            'text' => 'Metin',
                                            'textarea' => 'Uzun Metin',
                                            'number' => 'Numara',
                                        ])
                                        ->required(),
                                    Toggle::make('is_required')
                                        ->label('Zorunlu')
                                        ->default(false),
                                    Repeater::make('values')
                                        ->relationship()
                                        ->schema([
                                            Grid::make(12)
                                                ->schema([
                                                    TextInput::make('label')
                                                        ->label('Değer')
                                                        ->required()
                                                        ->columnSpan(4),
                                                    ColorPicker::make('color_hex')
                                                        ->label('Renk')
                                                        ->afterStateHydrated(function ($state, callable $set): void {
                                                            $set('color_hex', self::normalizeHex($state));
                                                        })
                                                        ->dehydrateStateUsing(function ($state): ?string {
                                                            return self::normalizeHex($state);
                                                        })
                                                        ->rules(['nullable', 'regex:/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/'])
                                                        ->columnSpan(4),
                                                    TextInput::make('price_delta')
                                                        ->numeric()
                                                        ->rules(['nullable', 'decimal:0,2'])
                                                        ->label('Fiyat farkı')
                                                        ->helperText('İsteğe bağlı')
                                                        ->columnSpan(2),
                                                    Toggle::make('is_default')
                                                        ->label('Varsayılan')
                                                        ->columnSpan(2),
                                                ])
                                                ->columnSpanFull(),
                                        ])
                                        ->addActionLabel('Değer ekle')
                                        ->visible(function (callable $get): bool {
                                            $type = $get('type');

                                            return in_array($type, ['button_group', 'select', 'radio'], true);
                                        }),
                                ])
                                ->addActionLabel('Seçenek ekle')
                                ->columnSpanFull(),
                        ]),
                ])
                    ->skippable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category')
                    ->sortable()
                    ->label('Kategori'),
                TextColumn::make('room_type')
                    ->label('Mekan Tipi')
                    ->toggleable(),
                TextColumn::make('material')
                    ->label('Malzeme')
                    ->toggleable(),
                TextColumn::make('base_price')
                    ->label('Temel Fiyat')
                    ->formatStateUsing(fn (?string $state) => $state ? self::formatCurrency($state) : '-'),
                TextColumn::make('shipping_cost')
                    ->label('Kargo Bedeli')
                    ->formatStateUsing(fn (?string $state) => $state !== null ? self::formatCurrency($state) : 'Belirlenecek'),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Aktif'),
            ])
	    ->headerActions([
    		CreateAction::make(),
            ])
            ->actions([
                \Filament\Tables\Actions\EditAction::make(),
		ViewAction::make()
            ])
            ->bulkActions([
                BulkAction::make('delete')
                    ->label('Seçilenleri sil')
                    ->requiresConfirmation()
                    ->action(function ($records): void {
                        foreach ($records as $record) {
                            $record->deleteWithRelations();
                        }
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
	    'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    private static function normalizeHex(?string $hex): ?string
    {
        if (! $hex) {
            return null;
        }

        $hex = trim($hex);
        $hex = Str::startsWith($hex, '#') ? $hex : '#'.$hex;

        return strtoupper($hex);
    }

    private static function formatCurrency(string|float|int $amount): string
    {
        return '₺'.number_format((float) $amount, 2, ',', '.');
    }
}
