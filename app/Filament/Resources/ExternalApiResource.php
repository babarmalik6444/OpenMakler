<?php

namespace App\Filament\Resources;

use App\ExternalApis\OpenImmoDriver;
use App\Filament\Resources\ExternalApiResource\Pages;
use App\Filament\Resources\ExternalApiResource\RelationManagers;
use App\Filament\SidebarLayout;
use App\Models\ExternalApi;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class ExternalApiResource extends Resource
{
    protected static ?string $model = ExternalApi::class;
    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $label = "Externe Api";
    protected static ?string $pluralLabel = "Externe Api's";


    public static function form(Form $form): Form
    {
        return $form
            ->schema(SidebarLayout::make()
                ->addTab([
                    Forms\Components\TextInput::make('name')
                        ->label(__("Name"))
                        ->required(),
                    Forms\Components\Select::make("driver")
                        ->options([
                            OpenImmoDriver::class => "OpenImmoDriver"
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('server')
                        ->required(),
                    Forms\Components\TextInput::make('port')
                        ->numeric()
                        ->required(),
                ])
                ->toArray());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("name")
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make("server")
                    ->sortable()
                    ->searchable()
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
            'index' => Pages\ListExternalApis::route('/'),
            'create' => Pages\CreateExternalApi::route('/create'),
            'edit' => Pages\EditExternalApi::route('/{record}/edit'),
        ];
    }
}
