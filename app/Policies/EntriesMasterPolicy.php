<?php

namespace App\Policies;

use App\Models\EntriesMaster;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EntriesMasterPolicy
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
        return $user->hasPermission('access_entries_master');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EntriesMaster  $entriesMaster
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, EntriesMaster $entriesMaster)
    {
        return $user->hasPermission('view_entries_master');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('add_entries_master');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EntriesMaster  $entriesMaster
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, EntriesMaster $entriesMaster)
    {
        return $user->hasPermission('edit_entries_master');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EntriesMaster  $entriesMaster
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, EntriesMaster $entriesMaster)
    {
        return $user->hasPermission('delete_entries_master');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EntriesMaster  $entriesMaster
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, EntriesMaster $entriesMaster)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EntriesMaster  $entriesMaster
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, EntriesMaster $entriesMaster)
    {
        //
    }
}
