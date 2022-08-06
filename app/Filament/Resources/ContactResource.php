<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Filament\Resources\ContactResource\RelationManagers;
use App\Filament\SidebarLayout;
use App\Forms\Components\CountrySelect;
use App\Models\Contact;
use App\Tables\Columns\BelongsToColumn;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $label = "Kontakt";
    protected static ?string $pluralLabel = "Kontakte";


    public static function form(Form $form): Form
    {
        return $form
            ->schema(SidebarLayout::make()
                ->addTab([
                    Forms\Components\TextInput::make('vorname')
                        ->required(),
                    Forms\Components\TextInput::make('name')
                        ->required(),
                    Forms\Components\TextInput::make('firma')->columnSpan(2),
                    #Forms\Components\TextInput::make('zusatzfeld'),
                    Forms\Components\TextInput::make('strasse'),
                    Forms\Components\TextInput::make('hausnummer'),
                    Forms\Components\TextInput::make('plz')->label("PLZ"),
                    Forms\Components\TextInput::make('ort'),
                    #Forms\Components\TextInput::make('postfach'),
                    #Forms\Components\TextInput::make('postf_plz'),
                    #Forms\Components\TextInput::make('postf_ort'),
                    CountrySelect::make("land")->required(),
                    Forms\Components\TextInput::make('email_direkt')->label("E-Mail"),
                    Forms\Components\TextInput::make('tel_zentrale')->label("Telefon"),
                    Forms\Components\Textarea::make("freitextfeld")->label("Notizen")
                ])
                ->toArray());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                BelongsToColumn::make("company.name")
                    ->resource(CompanyResource::class)
                    ->hidden(fn($record) => !auth()->user()->isSystemAdminOrSystemUser()),
                Tables\Columns\TextColumn::make("vorname")
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make("name")
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make("firma")
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make("created_at")
                    ->label("Erstellt am")
                    ->date("d.m.Y")
                    ->sortable()
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
            'index' => Pages\ListContacts::route('/'),
            'create' => Pages\CreateContact::route('/create'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
        ];
    }
}
