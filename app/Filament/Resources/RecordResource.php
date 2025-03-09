<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecordResource\Pages;
use App\Models\Record;
use App\Models\SubCategory;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RecordResource extends Resource
{
    protected static ?string $model = Record::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canViewAny(): bool
    {
        // Only show the resource if the user has the 'view_any' permission for Record
        return auth()->user()->can('view_any_record');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->required(),

                Select::make('category_id')

                    ->relationship(name: 'category', titleAttribute: 'category_name')
                    ->required()
                    ->reactive(),

                Select::make('subcategory_id')
                    ->label('Sub Category')
                    ->options(function (Get $get) {

                        $categoryId = $get('category_id');

                        return SubCategory::where('category_id', $categoryId)->pluck('name', 'id')->toArray();
                    })
            //        ->relationship(name: 'subcategory', titleAttribute: 'name')
                    ->required(),

                TextInput::make('description')->required(),
                FileUpload::make('attachment')->required(),

                Hidden::make('user_id')
               // ->hidden()
                    ->default(auth()->id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListRecords::route('/'),
            'create' => Pages\CreateRecord::route('/create'),
            'edit' => Pages\EditRecord::route('/{record}/edit'),
        ];
    }
}
