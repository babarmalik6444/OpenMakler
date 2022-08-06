<?php

namespace App\Filament\Resources\CustomerRequestResource\Pages;

use App\Filament\Resources\CustomerRequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustomerRequest extends EditRecord
{
    protected static string $resource = CustomerRequestResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
