<?php

namespace App\Policies;

use App\Models\SubSubSection;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubSubSectionPolicy
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
        return $user->hasPermission('browse_sub_sub_section');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SubSubSection  $subSubSection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, SubSubSection $subSubSection)
    {
        return $user->hasPermission('read_sub_sub_section');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('add_sub_sub_section');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SubSubSection  $subSubSection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, SubSubSection $subSubSection)
    {
        return $user->hasPermission('edit_sub_sub_section');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SubSubSection  $subSubSection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, SubSubSection $subSubSection)
    {
        return $user->hasPermission('delete_sub_sub_section');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SubSubSection  $subSubSection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, SubSubSection $subSubSection)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SubSubSection  $subSubSection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, SubSubSection $subSubSection)
    {
        //
    }
}
