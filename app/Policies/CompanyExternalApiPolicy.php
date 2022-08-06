<?php

namespace App\Policies;

use App\Models\CompanyExternalApi;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyExternalApiPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user): bool
    {
        return true;
    }


    public function view(User $user, CompanyExternalApi $companyExternalApi): bool
    {
        return !$companyExternalApi->exists || $companyExternalApi->belongsToCompany($user);
    }


    public function create(User $user): bool
    {
        return $user->isOwner();
    }


    public function update(User $user, CompanyExternalApi $companyExternalApi): bool
    {
        return $user->isOwner();
    }


    public function delete(User $user, CompanyExternalApi $companyExternalApi): bool
    {
        return $user->isOwner();
    }


    public function restore(User $user, CompanyExternalApi $companyExternalApi): bool
    {
        //
    }


    public function forceDelete(User $user, CompanyExternalApi $companyExternalApi): bool
    {
        //
    }
}
