<?php

namespace App\Policies;

use App\Enum\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RolePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->hasPermissionTo(Permission::VIEW_MANAGE_ROLE)?Response::allow():Response::deny('You do not have permission to view any roles.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Role $role): Response
    {
        return $user->hasPermissionTo(Permission::VIEW_MANAGE_ROLE)?Response::allow():Response::deny('You do not have permission to view roles.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->hasPermissionTo(Permission::CREATE_MANAGE_ROLE)?Response::allow():Response::deny('You do not have permission to create roles.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Role $role): Response
    {
        return $user->hasPermissionTo(Permission::EDIT_MANAGE_ROLE)?Response::allow():Response::deny('You do not have permission to update roles.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Role $role): Response
    {
        return $user->hasPermissionTo(Permission::DELETE_MANAGE_ROLE)?Response::allow():Response::deny('You do not have permission to delete roles.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Role $role): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Role $role): bool
    {
        return false;
    }
}
