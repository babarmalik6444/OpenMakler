<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\SidebarLayout;
use App\Models\User;
use App\Tables\Columns\BelongsToColumn;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use STS\FilamentImpersonate\Impersonate;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $label = "Benutzer";
    protected static ?string $pluralLabel = "Benutzer";

    public static function form(Form $form): Form
    {
        $record = auth()->user();
        //dd($record->isSystemAdminOrSystemUser());
        return $form
            ->schema(
                SidebarLayout::make()
                    ->addTab([
                        Forms\Components\TextInput::make('name')
                            ->label(__("Name"))
                            ->required(),
                        //->unique(ignorable: $this->model),
                        Forms\Components\TextInput::make('email')
                            ->label(__("E-Mail"))
                            ->email()
                            ->required(),

                        // Company
                        Forms\Components\BelongsToSelect::make("company_id")
                            ->label("Unternehmen")
                            ->relationship("company", "name")
                            ->hidden(fn($record): bool => !empty($record)?$record->isSystemAdminOrSystemUser():false),

                        // Company Office
                        Forms\Components\BelongsToSelect::make("company_office_id")
                            ->required(fn(User $record): bool => !empty($record)? !$record->isSystemAdminOrSystemUser():true)
                            ->label(__("FirmenZweigstelle"))
                            ->relationship("companyOffice", "name")
                            ->hidden(fn($record): bool => !empty($record)?$record->isSystemAdminOrSystemUser():true),

                        // User role
                        Forms\Components\BelongsToSelect::make("user_role_id")
                            ->label(__("User Rolle"))
                            ->hidden(fn() => !$record->isSystemAdminOrSystemUser())
                            ->disabled(fn() => !$record->isSystemAdmin())
                            ->relationship('userRole', 'name'),
                        Forms\Components\Placeholder::make(__("User Rolle"))
                            ->label(__("User Rolle"))
                            ->content(fn ($record): string => !empty($record)?(optional($record->userRole)->name ?: ''):'')
                            ->hidden(fn($record) => (!empty($record)?$record->isSystemAdminOrSystemUser():false) || (!empty($record)?!$record->exists:false)),

                    ])
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
                Tables\Columns\TextColumn::make("email")
                    ->label(__("E-Mail"))
                    ->sortable()
                    ->searchable(),
                BelongsToColumn::make("company.name")
                    ->resource(CompanyResource::class)
                    ->label(__("Firma"))
                    ->hidden(fn($record) => !auth()->user()->isSystemAdminOrSystemUser())
                    ->sortable()
                    ->searchable(),
                BelongsToColumn::make("companyOffice.name")
                    ->resource(CompanyOfficeResource::class)
                    ->label(__("Zweigstelle"))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make("userRole.name")
                    ->label(__("Rolle"))
                    ->hidden(fn($record) => !auth()->user()->isSystemAdminOrSystemUser())
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Impersonate::make('impersonate'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
