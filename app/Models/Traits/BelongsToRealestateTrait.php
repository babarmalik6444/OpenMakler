<?php

namespace App\Models\Traits;

use App\Models\Company;
use App\Models\Openimmo\RealEstate;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $realestate_id
 * @property RealEstate $realestate
 */
trait BelongsToRealestateTrait
{
    use SoftDeletes;

    public function realestate(): BelongsTo
    {
        return $this->belongsTo(RealEstate::class, "realestate_id");
    }
}
