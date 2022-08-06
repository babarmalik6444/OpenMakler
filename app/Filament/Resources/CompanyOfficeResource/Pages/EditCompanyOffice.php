<?php

namespace App\Filament\Resources\CompanyOfficeResource\Pages;

use App\Filament\Resources\CompanyOfficeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompanyOffice extends EditRecord
{
    protected static string $resource = CompanyOfficeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
