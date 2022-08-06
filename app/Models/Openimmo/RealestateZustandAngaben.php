<?php

namespace App\Models\Openimmo;

use App\Models\Traits\BelongsToRealestateTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property ZustandArt $zustandArt
 */
class RealestateZustandAngaben extends Model
{
    use BelongsToRealestateTrait;

    public $table = "openimmo_realestate_zustand_angaben";


    public function zustandArt(): BelongsTo
    {
        return $this->belongsTo(ZustandArt::class, "zustand_art");
    }
}
