<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Models\Appointment;
use App\Models\Employee;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-s-clock';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required()
                    ->label('Appointment Name'),

                Select::make('employee_id')
                    ->relationship(name: 'employee', titleAttribute: 'name')
                    ->searchable()

                    ->getSearchResultsUsing(fn (string $query) => Employee::query()
                        ->where('name', 'like', "%{$query}%")
                        ->limit(50)
                        ->get()
                        ->mapWithKeys(function ($employee) {
                            return [$employee->id => $employee->name.' - '.$employee->ic_no]; // Customize label: "name - email"
                        })
                    )
                    ->getOptionLabelUsing(fn ($value) => \App\Models\Employee::find($value)->name.' - '.\App\Models\Employee::find($value)->ic_no) // Display "name - email"
                    ->required(),

                DatePicker::make('date')
                    ->required()
                    ->format('d/m/Y'),

                TextInput::make('remarks')->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('employee.name')
                    ->sortable(),
                TextColumn::make('date')
                    ->dateTime('d/m/Y')
                    ->sortable(),
                TextColumn::make('remarks'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'view' => Pages\ViewAppointment::route('/{record}'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
