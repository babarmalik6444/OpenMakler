<?php

namespace App\Filament\Resources\CompanyExternalApiResource\Pages;

use App\Filament\Resources\CompanyExternalApiResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompanyExternalApis extends ListRecords
{
    protected static string $resource = CompanyExternalApiResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
