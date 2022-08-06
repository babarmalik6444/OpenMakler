<?php

namespace App\Filament\Resources;

use App\CaravelAdmin\Resources\CompanyOffice\RelatedUserTable;
use App\Filament\Resources\CompanyOfficeResource\Pages;
use App\Filament\Resources\CompanyOfficeResource\RelationManagers;
use App\Filament\SidebarLayout;
use App\Models\CompanyOffice;
use App\Tables\Columns\BelongsToColumn;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompanyOfficeResource extends Resource
{
    protected static ?string $model = CompanyOffice::class;
    protected static ?string $navigationIcon = 'heroicon-o-office-building';
    protected static ?string $label = "Zweigstelle";
    protected static ?string $pluralLabel = "Zweigstellen";


    public static function form(Form $form): Form
    {
        return $form
            ->schema(SidebarLayout::make()
                ->addTab([
                    Forms\Components\TextInput::make('name')
                        ->label(__("Name"))
                        ->required(),
                    Forms\Components\TextInput::make('email')
                        ->label(__("E-Mail"))
                        ->email()
                        ->required(),
                    Forms\Components\TextInput::make('phone')
                        ->label(__("Telefon"))
                        ->required(),
                    Forms\Components\TextInput::make('strasse')
                        ->label(__("Straße"))
                        ->required(),
                    Forms\Components\TextInput::make('hausnummer')
                        ->label(__("Hausnummer"))
                        ->required(),
                    Forms\Components\TextInput::make('plz')
                        ->label(__("PLZ"))
                        ->required(),
                    Forms\Components\TextInput::make('ort')
                        ->label(__("Ort"))
                        ->required(),
                    Forms\Components\TextInput::make('postfach')
                        ->label(__("Postfach"))
                        ->required(),
                ], null, 2)
                ->toArray());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("name")
                    ->sortable()
                    ->searchable(),
                BelongsToColumn::make("company.name")
                    ->label("Unternehmen")
                    ->resource(CompanyResource::class)
                    ->visible(fn() => auth()->user()->isSystemAdminOrSystemUser())
                    ->sortable()
                    ->searchable(),
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
            RelationManagers\EmployeesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanyOffices::route('/'),
            'create' => Pages\CreateCompanyOffice::route('/create'),
            'edit' => Pages\EditCompanyOffice::route('/{record}/edit'),
        ];
    }
}
