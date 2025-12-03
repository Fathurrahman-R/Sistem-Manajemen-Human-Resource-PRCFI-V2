<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $users = \App\Permissions\Permission::GROUP_PERMISSION_MANAGE_USERS;
        foreach($users as $user) {
            Permission::create([
                'name' => $user,
                'group' => 'Manajemen User',
            ]);
        }

        $karyawans = \App\Permissions\Permission::GROUP_PERMISSION_MANAGE_KARYAWAN;
        foreach($karyawans as $karyawan) {
            Permission::create([
                'name' => $karyawan,
                'group' => 'Manajemen Karyawan',
            ]);
        }

        $roles = \App\Permissions\Permission::GROUP_PERMISSION_MANAGE_ROLE;
        foreach($roles as $role) {
            Permission::create([
                'name' => $role,
                'group' => 'Manajemen Role',
            ]);
        }

        $programs = \App\Permissions\Permission::GROUP_PERMISSION_MANAGE_PROGRAM;
        foreach($programs as $program) {
            Permission::create([
                'name' => $program,
                'group' => 'Manajemen Program',
            ]);
        }

        $m_cuti = \App\Permissions\Permission::GROUP_PERMISSION_MANAGE_CUTI;
        foreach($m_cuti as $cuti) {
            Permission::create([
                'name' => $cuti,
                'group' => 'Manajemen Cuti',
            ]);
        }

        $ca_cuti = \App\Permissions\Permission::GROUP_PERMISSION_ADMIN_CONTROL_CUTI;
        foreach($ca_cuti as $cuti) {
            Permission::create([
                'name' => $cuti,
                'group' => 'Control Cuti',
            ]);
        }

        $cd_cuti = \App\Permissions\Permission::GROUP_PERMISSION_DIREKTUR_CONTROL_CUTI;
        foreach($cd_cuti as $cuti) {
            Permission::create([
                'name' => $cuti,
                'group' => 'Control Cuti',
            ]);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $superadmin = Role::create(['name'=>\App\Enum\Role::SUPERADMIN]);
        foreach (\App\Permissions\Permission::GROUP_SUPERADMIN_PERMISSION as $superadminPermission) {
            if (!is_array($superadminPermission)){
                $superadmin->givePermissionTo($superadminPermission);
                continue;
            }
            foreach ($superadminPermission as $permission) {
                $superadmin->givePermissionTo($permission);
            }
        }
        $karyawan = Role::create(['name'=>\App\Enum\Role::KARYAWAN]);
        foreach (\App\Permissions\Permission::GROUP_KARYAWAN_PERMISSION as $karyawanPermission) {
            if (!is_array($karyawanPermission)){
                $karyawan->givePermissionTo($karyawanPermission);
                continue;
            }
            foreach ($karyawanPermission as $permission) {
                $karyawan->givePermissionTo($permission);
            }
        }
        $admin = Role::create(['name'=>\App\Enum\Role::ADMIN]);
        foreach (\App\Permissions\Permission::GROUP_ADMIN_PERMISSION as $adminPermission) {
            if (!is_array($adminPermission)){
                $admin->givePermissionTo($adminPermission);
                continue;
            }
            foreach ($adminPermission as $permission) {
                $admin->givePermissionTo($permission);
            }
        }
        $direktur = Role::create(['name'=>\App\Enum\Role::DIREKTUR]);
        foreach (\App\Permissions\Permission::GROUP_DIREKTUR_PERMISSION as $direkturPermission) {
            if (!is_array($direkturPermission)){
                $direktur->givePermissionTo($direkturPermission);
                continue;
            }
            foreach ($direkturPermission as $permission) {
                $direktur->givePermissionTo($permission);
            }
        }
    }
}
