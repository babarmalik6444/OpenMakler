<?php

namespace App\Filament\Resources\TasklistResource\Pages;

use App\Filament\Resources\TasklistResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTasklist extends CreateRecord
{
    protected static string $resource = TasklistResource::class;
}
