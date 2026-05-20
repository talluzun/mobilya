<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Project;
use App\Models\Quote;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Toplam Ürün', Product::query()->count()),
            Stat::make('Toplam Teklif', Quote::query()->count()),
            Stat::make('Yeni Teklif', Quote::query()->where('status', 'new')->count()),
            Stat::make('Toplam Proje', Project::query()->count()),
        ];
    }
}
