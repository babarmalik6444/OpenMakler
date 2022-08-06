<?php

namespace App\Models\Traits;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $company_id
 * @property Company $company
 */
trait BelongsToCompanyTrait
{

    protected static function bootBelongsToCompanyTrait()
    {
        static::addGlobalScope('belongsToCompany', function (Builder $builder) {
            static::belongsToCompanyScopeFunction($builder);
        });

        // ADD company_id to creating
        static::creating(function($model){
            $user = auth()->hasUser() ? auth()->user() : null;

            if(optional($user)->company_id) {
                $model->company_id = $user->company_id;
            }
        });
    }


    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }


    protected static function belongsToCompanyScopeFunction(Builder &$builder)
    {
        $user = auth()->hasUser() ? auth()->user() : null;

        if(optional($user)->company_id) {
            $builder->where("company_id", $user->company_id);
        }
    }


    public function belongsToCompany(User $user): bool
    {
        return $user->company_id == $this->company_id;
    }
}
