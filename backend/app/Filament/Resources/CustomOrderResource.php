<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomOrderResource\Pages;
use App\Models\CustomOrder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CustomOrderResource extends Resource
{
    protected static ?string $model = CustomOrder::class;
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationLabel = 'Özel Siparişler';
    protected static ?string $modelLabel = 'Özel Sipariş';
    protected static ?string $pluralModelLabel = 'Özel Siparişler';
    protected static ?string $navigationGroup = 'Talepler';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('full_name')->label('Ad Soyad')->required(),
            TextInput::make('phone')->label('Telefon')->required(),
            TextInput::make('email')->label('E-posta')->email()->required(),
            TextInput::make('company_name')->label('Şirket Adı'),
            TextInput::make('product_type')->label('Ürün Tipi')->required(),
            TextInput::make('measurements')->label('Ölçü'),
            TextInput::make('quantity')->label('Adet')->numeric()->required(),
            TextInput::make('color_request')->label('Renk/Kaplama İsteği'),
            Select::make('status')->label('Durum')->options([
                'new' => 'Yeni',
                'reviewing' => 'İnceleniyor',
                'priced' => 'Fiyat Verildi',
                'closed' => 'Kapandı',
            ])->required(),
            Textarea::make('description')->label('Açıklama')->columnSpanFull(),
            Textarea::make('internal_note')->label('İç Not')->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')->label('Ad Soyad')->searchable(),
                TextColumn::make('product_type')->label('Ürün Tipi')->searchable(),
                TextColumn::make('quantity')->label('Adet')->sortable(),
                TextColumn::make('status')->label('Durum')->badge(),
                TextColumn::make('created_at')->label('Tarih')->dateTime('d.m.Y H:i')->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->label('Durum')->options([
                    'new' => 'Yeni',
                    'reviewing' => 'İnceleniyor',
                    'priced' => 'Fiyat Verildi',
                    'closed' => 'Kapandı',
                ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomOrders::route('/'),
            'view' => Pages\ViewCustomOrder::route('/{record}'),
            'edit' => Pages\EditCustomOrder::route('/{record}/edit'),
        ];
    }
}
