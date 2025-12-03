<?php

namespace App\Filament\Resources\Cutis\Pages;

use App\Enum\Cuti\StatusPengajuan;
use App\Filament\Resources\Cutis\CutiResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditCuti extends EditRecord
{
    protected static string $resource = CutiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->disabled(fn($record)=>$record->status!==StatusPengajuan::Diajukan->value)
                ->after(function ($record) {
                    // Hapus semua files terkait
                    if ($record->file_path && Storage::disk('public')->exists($record->file_path)) {
                        Storage::disk('public')->delete($record->file_path);
                    }
                    if ($record->signature_karyawan && Storage::disk('public')->exists($record->signature_karyawan)) {
                        Storage::disk('public')->delete($record->signature_karyawan);
                    }
                    if ($record->signature_direktur && Storage::disk('public')->exists($record->signature_direktur)) {
                        Storage::disk('public')->delete($record->signature_direktur);
                    }
                    if ($record->lampiran && is_array($record->lampiran)) {
                        foreach ($record->lampiran as $file) {
                            if (Storage::exists($file)) {
                                Storage::delete($file);
                            }
                        }
                    }
                }),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->disabled(fn($record)=>$record->status!==StatusPengajuan::Diajukan->value),
            $this->getCancelFormAction(),
        ];
    }
}
