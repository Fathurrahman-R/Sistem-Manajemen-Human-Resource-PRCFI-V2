<?php

namespace App\Filament\Resources\Master\Karyawans\Pages;

use App\Enum\Role;
use App\Filament\Resources\Master\Karyawans\KaryawanResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class CreateKaryawan extends CreateRecord
{
    protected static string $resource = KaryawanResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Buat record karyawan terlebih dahulu
        $karyawan = static::getResource()::getModel()::create($data);

        $user = User::where('email', $karyawan->email)->first();
        $user->assignRole(Role::KARYAWAN);

        return $karyawan;
    }
}
