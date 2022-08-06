<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerRequestResource\Pages;
use App\Filament\Resources\CustomerRequestResource\RelationManagers;
use App\Filament\SidebarLayout;
use App\Models\CustomerRequest;
use App\Tables\Columns\BelongsToColumn;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerRequestResource extends Resource
{
    protected static ?string $model = CustomerRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $label = "Gesuch";
    protected static ?string $pluralLabel = "Gesuche";


    public static function form(Form $form): Form
    {
        return $form
            ->schema(SidebarLayout::make()
                ->addTab([
                    Forms\Components\BelongsToSelect::make("contact_id")
                        ->relationship("contact", "fullname")
                        ->searchable()
                        ->preload()
                        ->label("Kontakt")
                        ->columnSpan(2),
                    Forms\Components\BelongsToSelect::make("realestate_id")
                        ->relationship("realestate", "objekttitel")
                        ->preload()
                        ->searchable(),
                    Forms\Components\TextInput::make('name')
                        ->label(__("Name")),
                    Forms\Components\TextInput::make('email')
                        ->label(__("E-Mail"))
                        ->email(),
                    Forms\Components\TextInput::make('phone')
                        ->label(__("Telefon")),
                    Forms\Components\Textarea::make("message")
                        ->label("Nachricht")
                        ->columnSpan(2)
                        ->required()
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
                Tables\Columns\TextColumn::make("email")
                    ->sortable()
                    ->searchable(),
                //BelongsToColumn::make("realestate.objekttitel")
                //    ->resource(RealEstateResource::class),
                Tables\Columns\TextColumn::make("created_at")
                    ->label("Anfragedatum")
                    ->dateTime("d.m.Y H:i")
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
            'index' => Pages\ListCustomerRequests::route('/'),
            'create' => Pages\CreateCustomerRequest::route('/create'),
            'edit' => Pages\EditCustomerRequest::route('/{record}/edit'),
        ];
    }
}
