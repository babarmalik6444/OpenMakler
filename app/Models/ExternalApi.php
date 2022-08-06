<?php

namespace App\Models;

use App\ExternalApis\ExternalApiDriver;
use App\Models\Traits\HasNameTrait;
use Illuminate\Database\Eloquent\Model;

class ExternalApi extends Model
{
    use HasNameTrait;


    public function driver(): ExternalApiDriver
    {
        $class = $this->driver;

        return $class::make($this);
    }
}
