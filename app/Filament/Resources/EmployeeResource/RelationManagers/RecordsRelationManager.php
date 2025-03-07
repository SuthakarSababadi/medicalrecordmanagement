<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use App\Models\SubCategory;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RecordsRelationManager extends RelationManager
{
    protected static string $relationship = 'records';

    public function form(Form $form): Form
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

                    ->required(),

                TextInput::make('description')->required(),

                Hidden::make('user_id')
                    ->default(auth()->id()),

                FileUpload::make('attachment')->required()
                    ->preserveFilenames()
                    ->multiple(),

            ]);

    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('category.category_name')
                    ->label('Category'),
                TextColumn::make('subcategory.name')
                    ->label('Sub Category'),
                TextColumn::make('description'),

                TextColumn::make('attachment')
                    ->label('Attachments')
                    ->formatStateUsing(function ($state) {
                        // Check if the state is a string and split it into an array
                        if (is_string($state)) {
                            // Split the string by commas
                            $state = explode(',', $state);
                        }

                        // Ensure it's an array now
                        if (! is_array($state)) {
                            return ''; // Return empty if it's still not an array
                        }

                        // Generate HTML links for each file in the array
                        $fileLinks = array_map(function ($filePath) {
                            // Trim spaces just in case there are extra spaces in the file paths
                            $filePath = trim($filePath);

                            // Return the download link
                            return '<a href="'.asset('storage/'.$filePath).'" target="_blank">Download</a>';
                        }, $state);

                        // Join the links with a separator (e.g., ' | ')
                        return implode(' | ', $fileLinks);
                    })
                    ->html(), // E
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
