<?php

namespace App\Filament\Resources\ExternalApiResource\Pages;

use App\Filament\Resources\ExternalApiResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExternalApi extends EditRecord
{
    protected static string $resource = ExternalApiResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
