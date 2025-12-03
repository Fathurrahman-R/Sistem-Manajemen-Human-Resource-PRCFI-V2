<?php

namespace App\Policies;

use App\Enum\Permission;
use App\Models\Master\Karyawan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class KaryawanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->hasPermissionTo(Permission::VIEW_MANAGE_KARYAWAN)?Response::allow():Response::deny('You do not have permission to view any Karyawan.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Karyawan $karyawan): Response
    {
        return $user->hasPermissionTo(Permission::VIEW_MANAGE_KARYAWAN)?Response::allow():Response::deny('You do not have permission to view Karyawan.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->hasPermissionTo(Permission::CREATE_MANAGE_KARYAWAN)?Response::allow():Response::deny('You do not have permission to create Karyawan.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Karyawan $karyawan): Response
    {
        return $user->hasPermissionTo(Permission::EDIT_MANAGE_KARYAWAN)?Response::allow():Response::deny('You do not have permission to update Karyawan.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Karyawan $karyawan): Response
    {
        return $user->hasPermissionTo(Permission::DELETE_MANAGE_KARYAWAN)?Response::allow():Response::deny('You do not have permission to delete Karyawan.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Karyawan $karyawan): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Karyawan $karyawan): bool
    {
        return false;
    }
}
