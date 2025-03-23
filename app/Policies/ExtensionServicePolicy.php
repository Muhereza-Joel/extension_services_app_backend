<?php

namespace App\Policies;

use App\Models\ExtensionService;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ExtensionServicePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['root', 'rdmin'])
            && $user->hasPermissionTo('view_extension::service');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ExtensionService $extensionService): bool
    {
        return $user->hasRole(['root', 'admin'])
            && $user->hasPermissionTo('view_any_extension::service');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['root', 'admin'])
            && $user->hasPermissionTo('create_extension::service');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ExtensionService $extensionService): bool
    {
        return $user->hasRole(['root', 'admin'])
            && $user->hasPermissionTo('update_extension::service');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ExtensionService $extensionService): bool
    {
        return $user->hasRole(['root', 'admin'])
            && $user->hasPermissionTo('delete_extension::service');
    }


    public function deleteAny(User $user): bool
    {
        return $user->hasRole(['root', 'admin'])
            && $user->hasPermissionTo('delete_any_extension::service');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ExtensionService $extensionService): bool
    {
        return $user->hasRole(['root', 'admin'])
            && $user->hasPermissionTo('restore_extension::service');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ExtensionService $extensionService): bool
    {
        return $user->hasRole(['root', 'admin'])
            && $user->hasPermissionTo('force_delete_extension::service');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->hasRole(['root', 'admin'])
            && $user->hasPermissionTo('force_delete_any_extension::service');
    }
}
