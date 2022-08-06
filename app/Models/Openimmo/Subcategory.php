<?php

namespace App\Models\Openimmo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $maincategory_id
 * @property Maincategory $maincategory
 */
class Subcategory extends Model
{
    protected $table = "openimmo_subcategories";


    public function maincategory(): BelongsTo
    {
        return $this->belongsTo(Maincategory::class, "maincategory_id");
    }
}
