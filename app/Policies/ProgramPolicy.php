<?php

namespace App\Policies;

use App\Enum\Permission;
use App\Models\Master\Program;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProgramPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->hasPermissionTo(Permission::VIEW_MANAGE_PROGRAM)?Response::allow():Response::deny('You do not have permission to view any program.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Program $program): Response
    {
        return $user->hasPermissionTo(Permission::VIEW_MANAGE_PROGRAM)?Response::allow():Response::deny('You do not have permission to view program.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->hasPermissionTo(Permission::CREATE_MANAGE_PROGRAM)?Response::allow():Response::deny('You do not have permission to create program.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Program $program): Response
    {
        return $user->hasPermissionTo(Permission::EDIT_MANAGE_PROGRAM)?Response::allow():Response::deny('You do not have permission to update program.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Program $program): Response
    {
        return $user->hasPermissionTo(Permission::DELETE_MANAGE_PROGRAM)?Response::allow():Response::deny('You do not have permission to delete program.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Program $program): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Program $program): bool
    {
        return false;
    }
}
