<?php

namespace App\Models\Openimmo;

use App\Models\Traits\BelongsToCompanyTrait;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property User $user
 * @property RealEstate $realEstates
 */
class Import extends Model
{
    use BelongsToCompanyTrait;

    protected $table = "openimmo_imports";



    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function realEstates(): HasMany
    {
        return $this->hasMany(RealEstate::class, "import_id");
    }
}
