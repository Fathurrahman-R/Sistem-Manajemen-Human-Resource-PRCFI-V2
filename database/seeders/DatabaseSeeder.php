<?php

namespace Database\Seeders;

use App\Enum\Role;
use App\Models\Master\Karyawan;
use App\Models\User;
use App\Permissions\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
//    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);
        // Superadmin: Buat karyawan dulu, lalu user manual dengan role
        Karyawan::factory()->create([
            'nama_lengkap' => 'Superadmin',
            'email' => 'superadmin@e.com',
            'posisi' => 'Superadmin',
        ]);

        // Karyawan lain: Auto-create user via observer
        Karyawan::factory()->create([
            'nama_lengkap' => 'Direktur',
            'email' => 'direktur@e.com',
            'posisi' => 'Direktur',
        ]); // User otomatis dibuat setelah commit

        Karyawan::factory()->create([
            'nama_lengkap' => 'Admin',
            'email' => 'admin@e.com',
            'posisi' => 'Admin HR',
        ]);

        Karyawan::factory()->create([
            'nama_lengkap' => 'Karyawan',
            'email' => 'karyawan@e.com',
            'posisi' => 'Staff',
        ]);
    }
}
