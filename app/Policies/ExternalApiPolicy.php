<?php

namespace App\Policies;

use App\Models\ExternalApi;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExternalApiPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        //
    }


    public function viewAny(User $user): bool
    {
        return $user->isSystemAdminOrSystemUser();
    }


    public function view(User $user, ExternalApi $ExternalApi): bool
    {
        return $user->isSystemAdminOrSystemUser();
    }


    public function create(User $user): bool
    {
        return $user->isSystemAdmin();
    }


    public function update(User $user, ExternalApi $ExternalApi): bool
    {
        return $user->isSystemAdmin();
    }


    public function delete(User $user, ExternalApi $ExternalApi): bool
    {
        return $user->isSystemAdmin();
    }


    public function restore(User $user, ExternalApi $ExternalApi): bool
    {
        //
    }


    public function forceDelete(User $user, ExternalApi $ExternalApi): bool
    {
        //
    }
}
