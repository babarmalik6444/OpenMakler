<?php

namespace App\Filament\Resources\CompanyOfficeResource\Pages;

use App\Filament\Resources\CompanyOfficeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompanyOffices extends ListRecords
{
    protected static string $resource = CompanyOfficeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
