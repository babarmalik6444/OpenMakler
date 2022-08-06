<?php

namespace App\Models;

use App\Models\Traits\BelongsToCompanyTrait;
use App\Models\Traits\BelongsToRealestateTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerRequest extends Model
{
    use BelongsToCompanyTrait, BelongsToRealestateTrait;


    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }


    public function getName(): string
    {
        return $this->name;
    }
}
