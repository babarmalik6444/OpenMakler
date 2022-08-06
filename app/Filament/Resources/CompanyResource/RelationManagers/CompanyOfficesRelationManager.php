<?php

namespace App\Filament\Resources\CompanyResource\RelationManagers;

use App\Filament\Resources\CompanyOfficeResource;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompanyOfficesRelationManager extends RelationManager
{
    protected static string $relationship = 'companyOffices';
    protected static ?string $recordTitleAttribute = 'name';


    protected static function getPluralModelLabel(): string
    {
        return CompanyOfficeResource::getPluralModelLabel();
    }


    public static function form(Form $form): Form
    {
        return CompanyOfficeResource::form($form);
    }

    public static function table(Table $table): Table
    {
        return CompanyOfficeResource::table($table);
    }
}
