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

    public function updated(Karyawan $karyawan): void
    {
        if ($karyawan->isDirty('email') || $karyawan->isDirty('nama_lengkap')) {
            $user = User::where('email', $karyawan->getOriginal('email'))->first();

            if ($user) {
                $user->update([
                    'name' => $karyawan->nama_lengkap,
                    'email' => $karyawan->email,
                ]);
            }
        }
    }
}
