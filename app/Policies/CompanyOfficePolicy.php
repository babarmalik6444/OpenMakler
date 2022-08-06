<?php

namespace App\Policies;

use App\Models\CompanyOffice;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyOfficePolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user): bool
    {
        return true;
    }


    public function view(User $user, CompanyOffice $companyOffice): bool
    {
        return !$companyOffice->exists || $user->isSystemAdminOrSystemUser() || $companyOffice->company_id == $user->company_id;
    }


    public function create(User $user): bool
    {
        return $user->isOwner();
    }


    public function update(User $user, CompanyOffice $companyOffice): bool
    {
        return $user->isSystemAdminOrSystemUser() || $user->isOwner();
    }


    public function delete(User $user, CompanyOffice $companyOffice): bool
    {
        return $user->isSystemAdminOrSystemUser() || $user->isOwner();
    }


    public function restore(User $user, CompanyOffice $companyOffice): bool
    {
        return false;
    }


    public function forceDelete(User $user, CompanyOffice $companyOffice): bool
    {
        return false;
    }
}
