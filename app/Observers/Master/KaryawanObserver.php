<?php

namespace App\Observers\Master;

use App\Models\Master\Karyawan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KaryawanObserver
{
    public function created(Karyawan $karyawan): void
    {
        // Wrap dalam transaction terpisah agar karyawan sudah commit
        DB::afterCommit(function () use ($karyawan) {
            User::firstOrCreate(
                ['email' => $karyawan->email,'karyawan_id'=>$karyawan->id],
                [
                    'name' => $karyawan->nama_lengkap,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
        });
    }

    public function updating(Karyawan $karyawan): void
    {
        if (! $karyawan->isDirty(['email', 'nama_lengkap'])) {
            return;
        }

        $user = User::where('karyawan_id', $karyawan->id)->first();

        if (! $user) {
            return;
        }

        $user->fill([
            'name' => $karyawan->nama_lengkap,
            'email' => $karyawan->email,
        ])->saveQuietly();
    }
}
