<?php

namespace App\Filament\Widgets;

use App\Models\Quote;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestQuotes extends TableWidget
{
    protected static ?string $heading = 'Son Teklifler';

    protected function getTableQuery(): Builder
    {
        return Quote::query()->with('product')->latest()->limit(5);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('ref_code')->label('Kod'),
                TextColumn::make('product.name')->label('Ürün'),
                TextColumn::make('customer_full_name')->label('Müşteri'),
                TextColumn::make('status_label')->label('Durum')->badge(),
                TextColumn::make('created_at')->label('Tarih')->dateTime('d.m.Y H:i'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->url(fn (Quote $record): string => route('filament.admin.resources.quotes.view', $record)),
            ]);
    }
}
