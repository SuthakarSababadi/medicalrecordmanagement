<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use App\Models\SubCategory;
use Carbon\Carbon;
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
                TextInput::make('title')->required()
                    ->label('File Location'),

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

                Select::make('year')
                    ->label('Year')
                    ->options(function () {
                        // Generate years from 1900 to the current year + 10
                        $currentYear = Carbon::now()->year;
                        $years = range(1998, $currentYear);

                        return array_combine($years, $years); // Key-value pairs with year as both key and value
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
                TextColumn::make('title')->label('File Location')->searchable(),
                TextColumn::make('category.category_name')
                    ->label('Category')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('subcategory.name')
                    ->label('Sub Category')
                    ->searchable(),

                TextColumn::make('year')->label('Year')
                    ->searchable(),
                TextColumn::make('description'),

                TextColumn::make('attachment')
                    ->label('Attachments')
                    ->formatStateUsing(function ($record) {
                        // Check if the attachment field has any files
                        if (! empty($record->attachment)) {
                            // Decode the attachment field from JSON if necessary
                            $attachments = is_string($record->attachment) ? json_decode($record->attachment, true) : $record->attachment;

                            // Generate clickable links for each attachment
                            return collect($attachments)->map(function ($file) {
                                // $url = "/path/to/your/files/" . $file; // Adjust the path to your file storage
                                $url = asset('storage/'.$file);

                                return "<a href='{$url}' target='_blank'>{$file}</a>";
                            })->implode(', '); // Separate links with commas or line breaks
                        }

                        return 'No attachment';
                    })
                    ->html()
                    ->disabledClick(), // Render the HTML in the column

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                //   Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //      Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
