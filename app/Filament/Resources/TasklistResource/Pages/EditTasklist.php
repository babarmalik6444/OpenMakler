<?php

namespace App\Filament\Resources\TasklistResource\Pages;

use App\Filament\Resources\TasklistResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTasklist extends EditRecord
{
    protected static string $resource = TasklistResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
