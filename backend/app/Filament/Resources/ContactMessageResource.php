<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;
    protected static ?string $navigationIcon = 'heroicon-o-inbox';
    protected static ?string $navigationLabel = 'İletişim Formları';
    protected static ?string $modelLabel = 'İletişim Formu';
    protected static ?string $pluralModelLabel = 'İletişim Formları';
    protected static ?string $navigationGroup = 'Talepler';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('full_name')->label('Ad Soyad')->required(),
            TextInput::make('email')->label('E-posta')->email()->required(),
            TextInput::make('phone')->label('Telefon'),
            TextInput::make('subject')->label('Konu'),
            Select::make('status')->label('Durum')->options([
                'new' => 'Yeni',
                'read' => 'Okundu',
                'replied' => 'Yanıtlandı',
            ])->required(),
            Textarea::make('message')->label('Mesaj')->disabled()->columnSpanFull(),
            Textarea::make('internal_note')->label('İç Not')->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')->label('Ad Soyad')->searchable(),
                TextColumn::make('email')->label('E-posta')->searchable(),
                TextColumn::make('subject')->label('Konu')->searchable(),
                TextColumn::make('status')->label('Durum')->badge(),
                TextColumn::make('created_at')->label('Tarih')->dateTime('d.m.Y H:i')->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->label('Durum')->options([
                    'new' => 'Yeni',
                    'read' => 'Okundu',
                    'replied' => 'Yanıtlandı',
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
            'index' => Pages\ListContactMessages::route('/'),
            'view' => Pages\ViewContactMessage::route('/{record}'),
            'edit' => Pages\EditContactMessage::route('/{record}/edit'),
        ];
    }
}
