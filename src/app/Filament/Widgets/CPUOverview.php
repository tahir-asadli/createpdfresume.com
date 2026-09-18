<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CPUOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';
    protected function getStats(): array
    {
        $cpu = getCpuInfo();
        $threadCount = isset($cpu['processor']) ? count($cpu['processor']) : '-';
        $coreCount = isset($cpu['cpu cores']) && isset($cpu['cpu cores'][0]) ? $cpu['cpu cores'][0] : '-';
        $cpuModel = isset($cpu['model name']) && isset($cpu['model name'][0]) ? $cpu['model name'][0] : '-';

        return [
            Stat::make('CPU Usage', getCpuUsage() . '%'),
            Stat::make('Model', $cpuModel),
            Stat::make('Thread', $threadCount),
            Stat::make('Core', $coreCount),
        ];
    }
}
