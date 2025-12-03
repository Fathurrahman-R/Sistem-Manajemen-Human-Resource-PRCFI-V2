<?php

namespace App\Enum;

enum Permission:string
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
}
