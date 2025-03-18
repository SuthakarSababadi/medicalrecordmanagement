<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestAppointment extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    protected static ?string $heading = 'Upcoming Appointments within 7 Days';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Appointment::query()->whereBetween('date', [now(), now()->addWeek()])

            )
            ->columns([
                TextColumn::make('employee.name'),
                TextColumn::make('category.category_name'),
                TextColumn::make('date')->dateTime('d/m/Y'),
            ]);
    }
}
