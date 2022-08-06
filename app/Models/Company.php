<?php

namespace App\Models;

use App\Models\Openimmo\RealEstate;
use App\Models\Traits\HasNameTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $name
 * @property int $owner_id
 * @property string $street
 * @property string $zip
 * @property string $city
 * @property string $country
 * @property string $phone
 * @property string $email
 * @property string $uid
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property Collection<\App\Models\CompanyExternalApi> $companyExternalApis
 */
class Company extends AbstractBaseModel
{
    use HasNameTrait;


    /**
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner_id");
    }


    /**
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, "company_id");
    }


    public function companyOffices(): HasMany
    {
        return $this->hasMany(CompanyOffice::class);
    }


    public function realEstates(): HasMany
    {
        return $this->hasMany(RealEstate::class, "company_id");
    }


    public function companyExternalApis(): HasMany
    {
        return $this->hasMany(CompanyExternalApi::class);
    }
}
