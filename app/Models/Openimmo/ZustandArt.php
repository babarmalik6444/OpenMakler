<?php

namespace App\Models\Openimmo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property \App\Models\Openimmo\RealestateZustandAngaben[] $realestateZustandAngaben;
 */
class ZustandArt extends Model
{
    protected $table = "openimmo_zustand_arten";


    public function realestateZustandAngaben(): BelongsToMany
    {
        return $this->belongsToMany(RealestateZustandAngaben::class, "zustand_art");
    }


    public static function findArt(string $art): ?self
    {
        return static::query()
            ->where("key", $art)
            ->first();
    }
}
