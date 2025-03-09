<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-s-user-group';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form

            ->schema([
                //  Section::make('Employee Details')
                //   ->schema([
                TextInput::make('name')->required(),
                TextInput::make('email')->email()->required(),
                TextInput::make('ic_no')->required()->unique(ignoreRecord: true),
                TextInput::make('phone_no')->required(),
                TextInput::make('address')->required(),
                // ])
                // ->columns(2),

                Select::make('citizenship')->options([
                    'Mal' => 'Malaysian',
                    'Ind' => 'Indonesian',
                    'other' => 'Other',
                ])->required(),

                TextInput::make('position')->required(),
                TextInput::make('worker_no')->required()->unique(ignoreRecord: true),
                Select::make('status')->options([
                    'Active' => 'Active',
                    'Inactive' => 'Inactive',
                ])->required(),

            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                //  TextColumn::make('email'),
                TextColumn::make('ic_no'),
                TextColumn::make('phone_no'),
                //   TextColumn::make('address'),
                //  TextColumn::make('citizenship'),
                //  TextColumn::make('position'),
                TextColumn::make('worker_no'),
                TextColumn::make('status'),

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
                    //  Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\RecordsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'view' => Pages\ViewEmployee::route('/{record}'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
