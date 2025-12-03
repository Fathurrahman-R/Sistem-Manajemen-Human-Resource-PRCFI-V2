<?php

namespace App\Policies;

use App\Enum\Permission;
use App\Models\Cuti;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CutiPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->hasPermissionTo(Permission::VIEW_MANAGE_CUTI)?Response::allow(): Response::deny('You do not have permission to view any cuti.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Cuti $cuti): Response
    {
        return $user->hasPermissionTo(Permission::VIEW_MANAGE_CUTI)?Response::allow(): Response::deny('You do not have permission to view any cuti.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->hasPermissionTo(Permission::CREATE_MANAGE_CUTI)?Response::allow(): Response::deny('You do not have permission to create any cuti.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Cuti $cuti): Response
    {
        return $user->hasPermissionTo(Permission::EDIT_MANAGE_CUTI)?Response::allow(): Response::deny('You do not have permission to update any cuti.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Cuti $cuti): Response
    {
        return $user->hasPermissionTo(Permission::DELETE_MANAGE_CUTI)?Response::allow(): Response::deny('You do not have permission to delete any cuti.');
    }

    public function direct(User $user):Response
    {
        return $user->hasPermissionTo(Permission::DIRECT_MANAGE_CUTI)?Response::allow(): Response::deny('You do not have permission to direct manage cuti.');
    }

    public function approve(User $user):Response
    {
        return $user->hasPermissionTo(Permission::APPROVE_MANAGE_CUTI)?Response::allow(): Response::deny('You do not have permission to approve manage cuti.');
    }

    public function reject(User $user):Response
    {
        return $user->hasPermissionTo(Permission::REJECT_MANAGE_CUTI)?Response::allow(): Response::deny('You do not have permission to reject manage cuti.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Cuti $cuti): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Cuti $cuti): bool
    {
        return false;
    }
}
