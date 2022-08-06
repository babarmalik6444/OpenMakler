<?php

namespace App\Filament\Resources\CustomerRequestResource\Pages;

use App\Filament\Resources\CustomerRequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomerRequests extends ListRecords
{
    protected static string $resource = CustomerRequestResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
