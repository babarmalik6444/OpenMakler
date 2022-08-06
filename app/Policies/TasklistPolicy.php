<?php

namespace App\Policies;

use App\Models\Tasklist;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TasklistPolicy
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
        return !$user->isSystemAdminOrSystemUser();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tasklist  $tasklist
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Tasklist $tasklist)
    {
        if(!$tasklist->exists) { // Neuer Eintrag
            return true;
        }
        if($tasklist->visibility == Tasklist::VISIBILITY_PRIVATE && $tasklist->user_id && $tasklist->user_id == $user->id) { // Eigener Eintrag
            return true;
        }
        if($tasklist->visibility == Tasklist::VISIBILITY_OFFICE && $tasklist->company_office_id && $tasklist->company_office_id == $user->company_office_id) { // Nur Büro
            return true;
        }
        if($tasklist->visibility == Tasklist::VISIBILITY_ALL && $tasklist->company_id && $tasklist->company_id == $user->company_id) { // Alle in firma
            return true;
        }

        return false;
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
     * @param  \App\Models\Tasklist  $tasklist
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Tasklist $tasklist)
    {
        return $this->view($user, $tasklist);
    }


    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tasklist  $tasklist
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Tasklist $tasklist)
    {
        return $this->view($user, $tasklist);
    }


    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tasklist  $tasklist
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Tasklist $tasklist)
    {
        //
    }


    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tasklist  $tasklist
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Tasklist $tasklist)
    {
        //
    }
}
