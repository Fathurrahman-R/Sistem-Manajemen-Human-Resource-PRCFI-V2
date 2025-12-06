<?php

namespace App\Permissions;

use chillerlan\Settings\SettingsContainerAbstract;

class Permission
{
    public const GROUP_SUPERADMIN_PERMISSION = [
        self::GROUP_PERMISSION_MANAGE_USERS,
        self::GROUP_PERMISSION_MANAGE_ROLE,
    ];
    public const GROUP_ADMIN_PERMISSION = [
        \App\Enum\Permission::VIEW_MANAGE_CUTI,
        \App\Enum\Permission::VIEW_MANAGE_TIMESHEET,
        \App\Enum\Permission::VIEW_MANAGE_ISI_TIMESHEET,
        self::GROUP_PERMISSION_MANAGE_KARYAWAN,
        self::GROUP_PERMISSION_MANAGE_PROGRAM,
        self::GROUP_PERMISSION_ADMIN_CONTROL_CUTI,
        self::GROUP_PERMISSION_ADMIN_CONTROL_TIMESHEET,
    ];
    public const GROUP_DIREKTUR_PERMISSION = [
        \App\Enum\Permission::VIEW_MANAGE_CUTI,
        \App\Enum\Permission::VIEW_MANAGE_TIMESHEET,
        \App\Enum\Permission::VIEW_MANAGE_ISI_TIMESHEET,
        self::GROUP_PERMISSION_DIREKTUR_CONTROL_CUTI,
        self::GROUP_PERMISSION_DIREKTUR_CONTROL_TIMESHEET,
    ];
    public const GROUP_KARYAWAN_PERMISSION = [
        self::GROUP_PERMISSION_MANAGE_CUTI,
        self::GROUP_PERMISSION_MANAGE_TIMESHEET,
        self::GROUP_PERMISSION_MANAGE_ISI_TIMESHEET,
    ];
    public const GROUP_PERMISSION_MANAGE_USERS = [
        \App\Enum\Permission::VIEW_MANAGE_USERS,
        \App\Enum\Permission::CREATE_MANAGE_USERS,
        \App\Enum\Permission::EDIT_MANAGE_USERS,
        \App\Enum\Permission::DELETE_MANAGE_USERS,
    ];
    public const GROUP_PERMISSION_MANAGE_KARYAWAN = [
        \App\Enum\Permission::VIEW_MANAGE_KARYAWAN,
        \App\Enum\Permission::CREATE_MANAGE_KARYAWAN,
        \App\Enum\Permission::EDIT_MANAGE_KARYAWAN,
        \App\Enum\Permission::DELETE_MANAGE_KARYAWAN,
    ];
    public const GROUP_PERMISSION_MANAGE_ROLE = [
        \App\Enum\Permission::VIEW_MANAGE_ROLE,
        \App\Enum\Permission::CREATE_MANAGE_ROLE,
        \App\Enum\Permission::EDIT_MANAGE_ROLE,
        \App\Enum\Permission::DELETE_MANAGE_ROLE,
    ];
    public const GROUP_PERMISSION_MANAGE_PROGRAM = [
        \App\Enum\Permission::VIEW_MANAGE_PROGRAM,
        \App\Enum\Permission::CREATE_MANAGE_PROGRAM,
        \App\Enum\Permission::EDIT_MANAGE_PROGRAM,
        \App\Enum\Permission::DELETE_MANAGE_PROGRAM,
    ];
    public const GROUP_PERMISSION_MANAGE_CUTI = [
        \App\Enum\Permission::VIEW_MANAGE_CUTI,
        \App\Enum\Permission::CREATE_MANAGE_CUTI,
        \App\Enum\Permission::EDIT_MANAGE_CUTI,
        \App\Enum\Permission::DELETE_MANAGE_CUTI,
    ];
    public const GROUP_PERMISSION_ADMIN_CONTROL_CUTI = [
        \App\Enum\Permission::DIRECT_MANAGE_CUTI,
    ];
    public const GROUP_PERMISSION_DIREKTUR_CONTROL_CUTI = [
        \App\Enum\Permission::APPROVE_MANAGE_CUTI,
        \App\Enum\Permission::REJECT_MANAGE_CUTI,
    ];
    public const GROUP_PERMISSION_MANAGE_TIMESHEET = [
        \App\Enum\Permission::VIEW_MANAGE_TIMESHEET,
        \App\Enum\Permission::CREATE_MANAGE_TIMESHEET,
        \App\Enum\Permission::EDIT_MANAGE_TIMESHEET,
        \App\Enum\Permission::DELETE_MANAGE_TIMESHEET,
    ];
    public const GROUP_PERMISSION_ADMIN_CONTROL_TIMESHEET = [
        \App\Enum\Permission::DIRECT_MANAGE_TIMESHEET,
    ];
    public const GROUP_PERMISSION_DIREKTUR_CONTROL_TIMESHEET = [
        \App\Enum\Permission::APPROVE_MANAGE_TIMESHEET,
        \App\Enum\Permission::REJECT_MANAGE_TIMESHEET,
    ];
    public const GROUP_PERMISSION_MANAGE_ISI_TIMESHEET = [
        \App\Enum\Permission::VIEW_MANAGE_ISI_TIMESHEET,
        \App\Enum\Permission::CREATE_MANAGE_ISI_TIMESHEET,
        \App\Enum\Permission::EDIT_MANAGE_ISI_TIMESHEET,
        \App\Enum\Permission::DELETE_MANAGE_ISI_TIMESHEET,
    ];
}
