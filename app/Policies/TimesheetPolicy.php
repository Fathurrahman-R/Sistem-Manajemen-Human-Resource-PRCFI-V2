<?php

namespace App\Policies;

use App\Enum\Permission;
use App\Models\Timesheet;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TimesheetPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->hasPermissionTo(Permission::VIEW_MANAGE_TIMESHEET)?Response::allow():Response::deny('You do not have permission to view any timesheets.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Timesheet $timesheet): Response
    {
        return $user->hasPermissionTo(Permission::VIEW_MANAGE_TIMESHEET)?Response::allow():Response::deny('You do not have permission to view any timesheets.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->hasPermissionTo(Permission::CREATE_MANAGE_TIMESHEET) ? Response::allow() : Response::deny('You do not have permission to create timesheets.');
    }
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Timesheet $timesheet): Response
    {
        return $user->hasPermissionTo(Permission::EDIT_MANAGE_TIMESHEET)?Response::allow():Response::deny('You do not have permission to update timesheets.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): Response
    {
        return $user->hasPermissionTo(Permission::DELETE_MANAGE_TIMESHEET)?Response::allow():Response::deny('You do not have permission to delete timesheets.');
    }

    public function direct(User $user, Timesheet $timesheet): Response
    {
        return $user->hasPermissionTo(Permission::DIRECT_MANAGE_TIMESHEET)?Response::allow():Response::deny('You do not have permission to direct timesheets.');
    }

    public function approve(User $user, Timesheet $timesheet): Response
    {
        return $user->hasPermissionTo(Permission::APPROVE_MANAGE_TIMESHEET)?Response::allow():Response::deny('You do not have permission to approve timesheets.');
    }

    public function reject(User $user, Timesheet $timesheet): Response
    {
        return $user->hasPermissionTo(Permission::REJECT_MANAGE_TIMESHEET)?Response::allow():Response::deny('You do not have permission to reject timesheets.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Timesheet $timesheet): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Timesheet $timesheet): bool
    {
        return false;
    }
}
