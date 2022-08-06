<?php

namespace App\Policies;

use App\Models\CustomerRequest;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerRequestPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomerRequest  $customerRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, CustomerRequest $customerRequest)
    {
        return $customerRequest->belongsTo($user);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return !$user->isSystemAdminOrSystemUser();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomerRequest  $customerRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, CustomerRequest $customerRequest)
    {
        return $this->view($user, $customerRequest);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomerRequest  $customerRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, CustomerRequest $customerRequest)
    {
        return $this->view($user, $customerRequest);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomerRequest  $customerRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, CustomerRequest $customerRequest)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomerRequest  $customerRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, CustomerRequest $customerRequest)
    {
        //
    }
}
