<?php

namespace App\Models\Openimmo;

use App\Models\Traits\BelongsToRealestateTrait;
use Illuminate\Database\Eloquent\Model;

class RealestateVerwaltungObjekt extends Model
{
    use BelongsToRealestateTrait;

    public $table = "openimmo_realestate_verwaltung_objekt";
}
