<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['root', 'rdmin'])
            && $user->hasPermissionTo('view_any_user');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $user_account): bool
    {
        return $user->hasRole(['root', 'admin'])
            && $user->hasPermissionTo('view_user');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['root', 'admin'])
            && $user->hasPermissionTo('create_user');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $user_account): bool
    {
        return $user->hasRole(['root', 'admin'])
            && $user->hasPermissionTo('update_user');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $user_account): bool
    {
        return $user->hasRole(['root', 'admin'])
            && $user->hasPermissionTo('delete_user');
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasRole(['root', 'admin'])
            && $user->hasPermissionTo('delete_any_user');
    }


    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $user_account): bool
    {
        return $user->hasRole(['root', 'admin'])
            && $user->hasPermissionTo('restore_user');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $user_account): bool
    {
        return $user->hasRole(['root', 'admin'])
            && $user->hasPermissionTo('force_delete_user');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->hasRole(['root', 'admin'])
            && $user->hasPermissionTo('force_delete_any_user');
    }
}
