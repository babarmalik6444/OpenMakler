<?php

namespace App\Models;

use App\Models\Traits\BelongsToCompanyTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property \App\Models\ExternalApi $externalApi
 */
class CompanyExternalApi extends Model
{
    use BelongsToCompanyTrait;
    protected $casts = [
        "settings" => "array"
    ];


    public function externalApi(): BelongsTo
    {
        return $this->belongsTo(ExternalApi::class);
    }


    public function getName(): string
    {
        return $this->externalApi->name;
    }
}
