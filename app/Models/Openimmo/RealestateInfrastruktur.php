<?php

namespace App\Models\Openimmo;

use App\Models\Traits\BelongsToRealestateTrait;
use Illuminate\Database\Eloquent\Model;

class RealestateInfrastruktur extends Model
{
    use BelongsToRealestateTrait;

    public $table = "openimmo_realestate_infrastruktur";


    public static function getAusblickOptions(): array
    {
        return  [
            "FERNE" => "Ferne",
            "SEE" => "See",
            "BERGE" => "Berge",
            "MEER" => "Meer",
        ];
    }
}
