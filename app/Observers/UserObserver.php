<?php

namespace App\Observers;

use App\Enum\Role;
use App\Models\Master\Karyawan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserObserver
{
    public function creating(User $user): void
    {
        // Jika superadmin, set role
        if ($user->email == 'superadmin@e.com') {
            $user->password = Hash::make('superadmin');
            $user->email_verified_at = now();
        }
    }

    public function created(User $user): void
    {
        // Assign role setelah user tersimpan
        if ($user->email == 'superadmin@e.com') {
            $user->assignRole(Role::SUPERADMIN);
        }else{
            $user->assignRole(Role::KARYAWAN);
        }
    }
}
