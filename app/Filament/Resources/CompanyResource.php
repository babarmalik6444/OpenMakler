<?php

namespace App\Filament\Resources;

use App\CaravelAdmin\Resources\Company\RelatedCompanyOfficeTable;
use App\Filament\Resources\CompanyResource\Pages;
use App\Filament\Resources\CompanyResource\RelationManagers\CompanyOfficesRelationManager;
use App\Filament\SidebarLayout;
use App\Forms\Components\CountrySelect;
use App\Forms\Components\StaticField;
use App\Models\Company;
use App\Tables\Columns\BelongsToColumn;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Forms;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $label = "Unternehmen";
    protected static ?string $pluralLabel = "Unternehmen";


    public static function form(Form $form): Form
    {
        return $form
            ->schema(SidebarLayout::make()
                ->addTab([
                    Forms\Components\TextInput::make('name')
                        ->label("Name")
                        ->required(),
                    //->unique(ignorable: $this->model),
                    Forms\Components\BelongsToSelect::make("owner_id")
                        ->label(__("Eigentümer"))
                        ->relationship("owner", "name")
                        ->disabled(fn($record): bool => $record->exists)
                        ->required(),
                    Forms\Components\TextInput::make('street')
                        ->label("Straße / Nr")
                        ->required(),
                    Forms\Components\TextInput::make('zip')
                        ->label("PLZ")
                        ->required(),
                    Forms\Components\TextInput::make('city')
                        ->label("Stadt")
                        ->required(),
                    CountrySelect::make("country")
                        ->required()
                        ->label("Land"),
                    Forms\Components\TextInput::make('email')
                        ->label("E-Mail")
                        ->email()
                        ->required(),
                    Forms\Components\TextInput::make('phone')
                        ->label("Telefon")
                        ->required(),
                    Forms\Components\TextInput::make('uid')
                        ->label("UID")
                        ->disabled(fn() => !auth()->user()->isOwner())
                        ->required(),
                ])
                ->addCard([
                    StaticField::make("id")->label("ID"),
                    Forms\Components\Placeholder::make("created_at")->label(__("Erstellt am"))
                        ->content(fn ($record): string => $record ? $record->updated_at->diffForHumans() : '-'),
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
                BelongsToColumn::make("owner.name")
                    ->resource(UserResource::class)
                    ->label(__("Besitzer"))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("created_at")
                    ->label("Erstellt am")
                    ->date("d.m.Y")
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
            CompanyOfficesRelationManager::class
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}
