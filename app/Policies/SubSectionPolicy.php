<?php

namespace App\Policies;

use App\Models\SubSection;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubSectionPolicy
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
        return $user->hasPermission('browse_sub_sections');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SubSection  $subSection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, SubSection $subSection)
    {
        return $user->hasPermission('read_sub_sections');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('add_sub_sections');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SubSection  $subSection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, SubSection $subSection)
    {
        return $user->hasPermission('edit_sub_sections');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SubSection  $subSection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, SubSection $subSection)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SubSection  $subSection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, SubSection $subSection)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SubSection  $subSection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, SubSection $subSection)
    {
        //
    }
}
