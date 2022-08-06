<?php

namespace App\Filament\Resources\ExternalApiResource\Pages;

use App\Filament\Resources\ExternalApiResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExternalApis extends ListRecords
{
    protected static string $resource = ExternalApiResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
