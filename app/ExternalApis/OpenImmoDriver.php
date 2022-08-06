<?php

namespace App\ExternalApis;

use App\Models\ExternalApi;
use Filament\Forms\Components\TextInput;

class OpenImmoDriver extends ExternalApiDriver
{
    protected string $server;
    protected ?int $port;
    protected ?string $directory;


    public static function make(ExternalApi $model): static
    {
        $obj = new static();
        $obj->model = $model;
        $obj->port = $model->port;
        $obj->server = $model->server;
        $obj->directory = $model->directory;

        return $obj;
    }


    public function schema(): array
    {
        return [
            TextInput::make("settings.username")
                ->label("Username")
                ->required(),
            //TextInput::make("password")
            //    ->label("Passwort")
            //    ->required()
        ];
    }
}
