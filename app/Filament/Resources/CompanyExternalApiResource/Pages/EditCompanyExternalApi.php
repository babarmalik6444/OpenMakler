<?php

namespace App\Filament\Resources\CompanyExternalApiResource\Pages;

use App\Filament\Resources\CompanyExternalApiResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompanyExternalApi extends EditRecord
{
    protected static string $resource = CompanyExternalApiResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
