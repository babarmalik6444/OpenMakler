<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TasklistResource\Pages;
use App\Filament\Resources\TasklistResource\RelationManagers;
use App\Filament\SidebarLayout;
use App\Forms\Components\Todolist;
use App\Models\Tasklist;
use App\Tables\Columns\HtmlColumn;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TasklistResource extends Resource
{
    protected static ?string $model = Tasklist::class;
    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $label = "Aufgabenliste";
    protected static ?string $pluralLabel = "Aufgabenlisten";


    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                SidebarLayout::make()
                    ->addTab([
                        Forms\Components\TextInput::make('name')
                            ->label(__("Name"))
                            ->required(),
                        Forms\Components\Select::make("visibility")
                            ->label("Sichtbarkeit")
                            ->required()
                            ->default(Tasklist::VISIBILITY_PRIVATE)
                            ->disabled(fn($record): bool => (bool)$record->exists)
                            ->options(Tasklist::getVisibilityOptions()),
                        Todolist::make("tasks")
                            ->columnSpan(2)
                            ->default(["active" => [""]])
                            ->label("Aufgaben")
                    ], "Aufgabenliste", 2)
                    ->toArray()
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("name")
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make("visibility")
                    ->label("Sichtbarkeit")
                    ->enum(Tasklist::getVisibilityOptions()),
                HtmlColumn::make('created_at')
                    ->label(__("Erstellt am"))
                    ->date("Y-m-d")
                    ->subtitle(fn($record) => $record->updated_at->format("H:i"))
                    ->sortable(),
                HtmlColumn::make('updated_at')
                    ->label(__("Geändert am"))
                    ->date("Y-m-d")
                    ->subtitle(fn($record) => $record->updated_at->format("H:i"))
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListTasklists::route('/'),
            'create' => Pages\CreateTasklist::route('/create'),
            'edit' => Pages\EditTasklist::route('/{record}/edit'),
        ];
    }
}
