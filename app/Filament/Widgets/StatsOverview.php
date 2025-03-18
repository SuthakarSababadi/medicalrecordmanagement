<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use App\Models\Employee;
use App\Models\Record;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Appointment', Appointment::count()),
            Stat::make('Total Employees', Employee::where('status', 'Active')->count()),
            Stat::make('Total File Scan', Record::count()),
        ];
    }
}
