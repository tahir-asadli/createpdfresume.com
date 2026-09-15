<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SystemOverview extends BaseWidget
{

    protected static ?string $pollingInterval = '15s';
    protected function getStats(): array
    {
        $RAM = getMemoryInfo();
        return [
            isset($RAM['total']) ? Stat::make('Total RAM', $RAM['total']) : Stat::make('Total RAM', 'No info')->color('danger'),
            isset($RAM['free']) ? Stat::make('Free RAM', $RAM['free']) : Stat::make('Free RAM', 'No info')->color('danger'),
            isset($RAM['available']) ? Stat::make('Available RAM', $RAM['available']) : Stat::make('Available RAM', 'No info')->color('danger'),
            isset($RAM['used']) ? Stat::make('Used RAM', $RAM['used']) : Stat::make('Used RAM', 'No info')->color('danger'),
        ];
    }
}
