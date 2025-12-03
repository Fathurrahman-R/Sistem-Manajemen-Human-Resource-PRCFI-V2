<?php

namespace App\Filament\Resources\Master\Karyawans\Pages;

use App\Filament\Resources\Master\Karyawans\KaryawanResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKaryawan extends ViewRecord
{
    protected static string $resource = KaryawanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
