<?php

namespace App\Models;

use App\Models\Traits\HasNameTrait;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserRole extends AbstractBaseModel
{
    use HasNameTrait;

    const ROLE_SYSTEM_ADMIN = 1;
    const ROLE_SYSTEM_USER = 2;
    const ROLE_OWNER = 3;
    const ROLE_USER = 4;
    const ROLE_FREELANCER = 5;


    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
