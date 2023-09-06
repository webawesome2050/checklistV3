<?php

namespace App\Policies;

use App\Models\CheckListItem;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CheckListItemPolicy
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
        return $user->hasPermission('access_checklist');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CheckListItem  $checkListItem
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, CheckListItem $checkListItem)
    {
        return $user->hasPermission('view_checklist');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('add_checklist');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CheckListItem  $checkListItem
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, CheckListItem $checkListItem)
    {
        return $user->hasPermission('edit_checklist');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CheckListItem  $checkListItem
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, CheckListItem $checkListItem)
    {
        return $user->hasPermission('delete_checklist');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CheckListItem  $checkListItem
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, CheckListItem $checkListItem)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CheckListItem  $checkListItem
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, CheckListItem $checkListItem)
    {
        //
    }
}
