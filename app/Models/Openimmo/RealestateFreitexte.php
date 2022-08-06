<?php

namespace App\Models\Openimmo;

use App\Models\Traits\BelongsToRealestateTrait;
use Illuminate\Database\Eloquent\Model;

class RealestateFreitexte extends Model
{
    use BelongsToRealestateTrait;

    public $table = "openimmo_realestate_freitexte";
}
