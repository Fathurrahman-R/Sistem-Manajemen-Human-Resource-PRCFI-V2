<?php

namespace App\Filament\Resources\Master\Karyawans\Pages;

use App\Enum\Master\StatusKerja;
use App\Filament\Resources\Master\Karyawans\KaryawanResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditKaryawan extends EditRecord
{
    protected static string $resource = KaryawanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['status']!==StatusKerja::Tetap->value?:$data['tanggal_expired']=null;
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['status']!==StatusKerja::Tetap->value?:$data['tanggal_expired']=null;
        return $data;
    }
}
