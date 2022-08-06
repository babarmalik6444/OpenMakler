<?php

namespace App\Models\Openimmo;

use App\Models\Traits\BelongsToRealestateTrait;
use Illuminate\Database\Eloquent\Model;

class RealestateAusstattung extends Model
{
    use BelongsToRealestateTrait;

    public $table = "openimmo_realestate_ausstattung";


    public function getBefeuerungString()
    {
        $array = [];
        foreach($this->attributesToArray() AS $type => $value) {
            if(\Str::startsWith($type, "befeuerung_") && $value) {
                $array[]= \Str::title(substr($type, 11));
            }
        }

        return implode(", ", $array);
    }
}
