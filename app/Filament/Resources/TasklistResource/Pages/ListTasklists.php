<?php

namespace App\Filament\Resources\TasklistResource\Pages;

use App\Filament\Resources\TasklistResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTasklists extends ListRecords
{
    protected static string $resource = TasklistResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
