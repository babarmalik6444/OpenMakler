<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserRoleResource\Pages;
use App\Filament\Resources\UserRoleResource\RelationManagers;
use App\Filament\SidebarLayout;
use App\Models\UserRole;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserRoleResource extends Resource
{
    protected static ?string $model = UserRole::class;
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $label = "Rechte & Rolle";
    protected static ?string $pluralLabel = "Rechte & Rollen";


    public static function form(Form $form): Form
    {
        return $form
            ->schema(SidebarLayout::make()
                ->addTab([
                    Forms\Components\TextInput::make('name')
                        ->label(__("Name"))
                        ->required(),
                        //->unique(ignorable: $this->rec),
                    //Section::make('Rechte')
                    //    ->schema([
                    //MultiCheckboxField::make('permissions')
                    //    ->relationship("permissions", "label")
                    //    ->disableLabel()
                    //    ->label("Rechte")
                    //    ->columns(4)
                    //    ->optionGroups(function($options){
                    //        $groups = [];
                    //        foreach($options AS $opt) {
                    //            // Get object+action
                    //            $parts = explode(" ", $opt["label"]);
                    //            $action = array_slice($parts, -1);
                    //            $object = implode(" ", array_slice($parts, 0, -1));
                    //
                    //            if(!isset($groups[$object])) $groups[$object] = [];
                    //
                    //            $groups[$object][] = [
                    //                "id" => $opt["id"],
                    //                "label" => ucfirst(end($action))
                    //            ];
                    //        }
                    //
                    //        return $groups;
                    //    })
                    //])
                ])
                ->toArray());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
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
            RelationManagers\UsersRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserRoles::route('/'),
            'create' => Pages\CreateUserRole::route('/create'),
            'edit' => Pages\EditUserRole::route('/{record}/edit'),
        ];
    }
}
