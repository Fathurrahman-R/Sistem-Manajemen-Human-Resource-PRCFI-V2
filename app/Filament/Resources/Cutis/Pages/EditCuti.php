<?php

namespace App\Filament\Resources\Cutis\Pages;

use App\Enum\Cuti\StatusPengajuan;
use App\Filament\Resources\Cutis\CutiResource;
use App\Models\Cuti;
use App\Services\CutiDocumentServiceNew;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditCuti extends EditRecord
{
    protected static string $resource = CutiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->disabled(fn($record)=>$record->status!==StatusPengajuan::Diajukan)
                ->after(function ($record) {
                    // Hapus semua files terkait
                    $service = app(CutiDocumentServiceNew::class);
                    $service->cleanupAllDocuments($record);
                }),
        ];
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $record = $this->record;
        $service = app(CutiDocumentServiceNew::class);

        // Cleanup old files, but preserve attachments kept by the user
        $service->cleanupForUpdate($record, $data['lampiran'] ?? []);

        // Save new signature from form input (upload or draw)
        $signaturePath = null;
        if (!empty($data['signature_png'])) {
            $signaturePath = $service->saveSignature($data['signature_png'], 'karyawan');
        } elseif (!empty($data['signature_drawn'])) {
            $signaturePath = $service->saveSignature($data['signature_drawn'], 'karyawan');
        }
        $data['signature_karyawan'] = $signaturePath;

        // Clear file_path to regenerate after save
        $data['file_path'] = null;

        return $data;
    }

    protected function afterSave(): void
    {
        try {
            $documentService = app(CutiDocumentServiceNew::class);
            $documentPath = $documentService->generateAndSaveCutiDocument($this->record);

            $this->record->update(['file_path' => $documentPath]);

            Notification::make()
                ->title('Pengajuan Diperbarui')
                ->body('Dokumen surat cuti telah dibuat ulang berdasarkan data terbaru.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error Generate Dokumen')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->before(function ($record) {

                })
                ->after(function ($record,$data) {

                })
                ->disabled(fn($record)=>$record->status!==StatusPengajuan::Diajukan),
            $this->getCancelFormAction(),
        ];
    }
}
