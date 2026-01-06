<?php

namespace App\Enum;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;

enum Permission:string implements HasColor
{
    // User
    case VIEW_MANAGE_USERS = 'lihat_manajemen_pengguna';
    case CREATE_MANAGE_USERS = 'tambah_manajemen_pengguna';
    case EDIT_MANAGE_USERS = 'ubah_manajemen_pengguna';
    case DELETE_MANAGE_USERS = 'hapus_manajemen_pengguna';

    // karyawan
    case VIEW_MANAGE_KARYAWAN = 'lihat_manajemen_karyawan';
    case CREATE_MANAGE_KARYAWAN = 'tambah_manajemen_karyawan';
    case EDIT_MANAGE_KARYAWAN = 'ubah_manajemen_karyawan';
    case DELETE_MANAGE_KARYAWAN = 'hapus_manajemen_karyawan';

    // Role
    case VIEW_MANAGE_ROLE = 'lihat_manajemen_role';
    case CREATE_MANAGE_ROLE = 'tambah_manajemen_role';
    case EDIT_MANAGE_ROLE = 'ubah_manajemen_role';
    case DELETE_MANAGE_ROLE = 'hapus_manajemen_role';

    // Program
    case VIEW_MANAGE_PROGRAM = 'lihat_manajemen_program';
    case CREATE_MANAGE_PROGRAM = 'tambah_manajemen_program';
    case EDIT_MANAGE_PROGRAM = 'ubah_manajemen_program';
    case DELETE_MANAGE_PROGRAM = 'hapus_manajemen_program';

    // Cuti
    case VIEW_MANAGE_CUTI = 'lihat_manajemen_cuti';
    case CREATE_MANAGE_CUTI = 'tambah_manajemen_cuti';
    case EDIT_MANAGE_CUTI = 'ubah_manajemen_cuti';
    case DELETE_MANAGE_CUTI = 'hapus_manajemen_cuti';
    case DIRECT_MANAGE_CUTI = 'teruskan_manajemen_cuti';
    case APPROVE_MANAGE_CUTI = 'setujui_manajemen_cuti';
    case REJECT_MANAGE_CUTI = 'tolak_manajemen_cuti';

    // Timesheet
    case VIEW_MANAGE_TIMESHEET = 'lihat_manajemen_timesheet';
    case CREATE_MANAGE_TIMESHEET = 'tambah_manajemen_timesheet';
    case EDIT_MANAGE_TIMESHEET = 'ubah_manajemen_timesheet';
    case DELETE_MANAGE_TIMESHEET = 'hapus_manajemen_timesheet';
    case DIRECT_MANAGE_TIMESHEET = 'teruskan_manajemen_timesheet';
    case APPROVE_MANAGE_TIMESHEET = 'setujui_manajemen_timesheet';
    case REJECT_MANAGE_TIMESHEET = 'tolak_manajemen_timesheet';

    // Isi Timesheet
    case VIEW_MANAGE_ISI_TIMESHEET = 'lihat_manajemen_isi_timesheet';
    case CREATE_MANAGE_ISI_TIMESHEET = 'tambah_manajemen_isi_timesheet';
    case EDIT_MANAGE_ISI_TIMESHEET = 'ubah_manajemen_isi_timesheet';
    case DELETE_MANAGE_ISI_TIMESHEET = 'hapus_manajemen_isi_timesheet';

    public function getColor(): string|array|null
    {
        return match ($this) {

            // Superadmin only permission
            self::VIEW_MANAGE_USERS,
            self::CREATE_MANAGE_USERS,
            self::EDIT_MANAGE_USERS,
            self::DELETE_MANAGE_USERS,

            self::VIEW_MANAGE_ROLE,
            self::CREATE_MANAGE_ROLE,
            self::EDIT_MANAGE_ROLE,
            self::DELETE_MANAGE_ROLE => Color::hex('#E11D48'),

            // Data management permission
            self::VIEW_MANAGE_KARYAWAN,
            self::CREATE_MANAGE_KARYAWAN,
            self::EDIT_MANAGE_KARYAWAN,
            self::DELETE_MANAGE_KARYAWAN,

            self::VIEW_MANAGE_PROGRAM,
            self::CREATE_MANAGE_PROGRAM,
            self::EDIT_MANAGE_PROGRAM,
            self::DELETE_MANAGE_PROGRAM => Color::hex('#0F766E'),

            // Document management permission
            self::VIEW_MANAGE_CUTI,
            self::CREATE_MANAGE_CUTI,
            self::EDIT_MANAGE_CUTI,
            self::DELETE_MANAGE_CUTI,

            self::VIEW_MANAGE_TIMESHEET,
            self::CREATE_MANAGE_TIMESHEET,
            self::EDIT_MANAGE_TIMESHEET,
            self::DELETE_MANAGE_TIMESHEET => Color::hex('#10B981'),

            // Control permission
            self::DIRECT_MANAGE_CUTI,
            self::APPROVE_MANAGE_CUTI,
            self::REJECT_MANAGE_CUTI,

            self::DIRECT_MANAGE_TIMESHEET,
            self::APPROVE_MANAGE_TIMESHEET,
            self::REJECT_MANAGE_TIMESHEET => Color::hex('#FACC15'),

            // Personal data permission
            self::VIEW_MANAGE_ISI_TIMESHEET,
            self::CREATE_MANAGE_ISI_TIMESHEET,
            self::EDIT_MANAGE_ISI_TIMESHEET,
            self::DELETE_MANAGE_ISI_TIMESHEET => Color::hex('#F97316'),

            default => Color::Gray,
        };
    }
}
