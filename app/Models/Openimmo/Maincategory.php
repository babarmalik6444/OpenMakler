<?php

namespace App\Models\Openimmo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property \App\Models\Openimmo\Subcategory $subcategories
 */
class Maincategory extends Model
{
    protected $table = "openimmo_maincategories";


    public function subcategories(): HasMany
    {
        return $this->hasMany(Subcategory::class);
    }
}
