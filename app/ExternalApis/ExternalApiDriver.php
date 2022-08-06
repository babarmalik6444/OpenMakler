<?php

namespace App\ExternalApis;

use App\Models\ExternalApi;

abstract class ExternalApiDriver
{
    protected ExternalApi $model;


    abstract public static function make(ExternalApi $model): static;

    abstract public function schema(): array;
}
