<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuoteResource\Pages;
use App\Models\Quote;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class QuoteResource extends Resource
{
    protected static ?string $model = Quote::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Teklifler';
    protected static ?string $modelLabel = 'Teklif';
    protected static ?string $pluralModelLabel = 'Teklifler';
    protected static ?string $navigationGroup = 'Talepler';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Teklif Bilgileri')
                ->schema([
                    TextInput::make('ref_code')->label('Teklif Kodu')->disabled(),
                    Select::make('status')
                        ->label('Durum')
                        ->options(Quote::STATUSES)
                        ->required(),
                    TextInput::make('quantity')->label('Adet')->numeric()->disabled(),
                    TextInput::make('selected_color_label')->label('Seçilen Renk')->disabled(),
                ])
                ->columns(2),
            Section::make('Müşteri')
                ->schema([
                    TextInput::make('customer_first_name')->label('Ad'),
                    TextInput::make('customer_last_name')->label('Soyad'),
                    TextInput::make('customer_email')->label('E-posta'),
                    TextInput::make('customer_phone')->label('Telefon'),
                    TextInput::make('company_name')->label('Şirket Adı'),
                ])
                ->columns(2),
            Section::make('Notlar')
                ->schema([
                    Textarea::make('note')->label('Müşteri Mesajı')->disabled()->columnSpanFull(),
                    Textarea::make('internal_note')->label('İç Not')->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with('product'))
            ->columns([
                TextColumn::make('ref_code')->label('Teklif Kodu')->searchable()->sortable(),
                TextColumn::make('product.name')->label('Ürün')->searchable()->sortable(),
                TextColumn::make('customer_full_name')->label('Müşteri')->searchable(['customer_first_name', 'customer_last_name', 'customer_name']),
                TextColumn::make('customer_email')->label('E-posta')->searchable(),
                TextColumn::make('quantity')->label('Adet')->sortable(),
                TextColumn::make('status_label')->label('Durum')->badge(),
                TextColumn::make('created_at')->label('Tarih')->dateTime('d.m.Y H:i')->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->label('Durum')->options(Quote::STATUSES),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuotes::route('/'),
            'view' => Pages\ViewQuote::route('/{record}'),
            'edit' => Pages\EditQuote::route('/{record}/edit'),
        ];
    }
}
