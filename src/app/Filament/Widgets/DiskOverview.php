<?php

namespace App\Filament\Widgets;

use App\Models\Resume;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DiskOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';
    protected function getStats(): array
    {
        return [
            Stat::make('User templates files', userTemplateFilesCount())->color('danger'),
            Stat::make('User templates size', userTemplateSize())->color('success'),
            Stat::make('Resume Count', Resume::all()->count()),
        ];
    }
}
