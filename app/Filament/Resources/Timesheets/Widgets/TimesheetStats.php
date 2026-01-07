<?php

namespace App\Filament\Resources\Timesheets\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TimesheetStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Semua', 'xx')
        ];
    }
}
