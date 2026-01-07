<?php

namespace App\Filament\Resources\Cutis\Pages;

use App\Enum\Permission;
use App\Filament\Resources\Cutis\CutiResource;
use App\Jobs\Cuti\EmailPengajuanKeAdmin;
use App\Jobs\Cuti\EmailStatusPengajuan;
use App\Models\Cuti;
use App\Models\User;
use App\Notifications\Cuti\PengajuanCuti;
use App\Services\CutiDocumentServiceNew;
use App\Services\EmailNotificationService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CreateCuti extends CreateRecord
{
    protected static string $resource = CutiResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['karyawan_id'] = User::getKaryawanId(Auth::user());
        $data['signature_karyawan'] = $data['signature_png'];

        // Get signature from Livewire component if using draw method
//        $signatureDrawn = request()->input('signature_drawn');

        // Try to get from session/livewire state
//        if (!$signatureDrawn && session()->has('livewire.signature_pad.signatureData')) {
//            $signatureDrawn = session('livewire.signature_pad.signatureData');
//        }

//        \Log::info('CreateCuti - mutateFormDataBeforeCreate', [
//            'signature_method' => $data['signature_method'] ?? null,
//            'has_signature_png' => !empty($data['signature_png']),
//            'has_signature_drawn_from_data' => !empty($data['signature_drawn']),
//            'has_signature_drawn_from_request' => !empty($signatureDrawn),
//            'signature_drawn_length_data' => isset($data['signature_drawn']) ? strlen($data['signature_drawn']) : 0,
//            'signature_drawn_length_request' => $signatureDrawn ? strlen($signatureDrawn) : 0,
//        ]);

        // Simpan signature ke storage
//        $documentService = app(CutiDocumentServiceNew::class);
//        $signaturePath = null;
//        $signatureMethod = $data['signature_method'] ?? null;
//
//        if ($signatureMethod === 'upload' && !empty($data['signature_png'])) {
//            $signaturePath = $data['signature_png'];
//            \Log::info('Using upload signature', ['path' => $signaturePath]);
//        }

//        if ($signatureMethod === 'draw') {
//            // Try data array first, then request
//            $signaturePath = $data['signature_drawn'] ?? $signatureDrawn;
//            if ($signaturePath) {
//                \Log::info('Using draw signature', ['data_url_length' => strlen($signaturePath)]);
//            }
//        }

//        if ($signaturePath) {
//            try {
//                $savedPath = $documentService->saveSignature($signaturePath, 'karyawan');
//                $data['signature_karyawan'] = $savedPath;
//                \Log::info('Signature saved successfully', ['saved_path' => $savedPath]);
//            } catch (\Exception $e) {
//                \Log::error('Failed to save signature', ['error' => $e->getMessage()]);
//                throw $e;
//            }
//        } else {
//            \Log::warning('No signature path provided');
//        }
//
//        // Hapus field signature dari form data
//        unset($data['signature_method'], $data['signature_png'], $data['signature_drawn'], $data['signature_png_name']);

        return $data;
    }

    protected function beforeCreate(): void
    {
        // Store signature data from any Livewire components in form state
//        $formState = $this->form->getState();
//        \Log::info('beforeCreate - form state', ['keys' => array_keys($formState)]);
    }

    protected function afterCreate(): void
    {
        $record = $this->record;

        try {
            // Generate dokumen dengan signature yang sudah disimpan
            $documentService = app(CutiDocumentServiceNew::class);
            $documentPath = $documentService->generateAndSaveCutiDocument($record);

            // Update file_path
            $record->update(['file_path' => $documentPath]);


            Notification::make()
                ->title('Pengajuan Berhasil')
                ->body('Pengajuan cuti telah dibuat dan dokumen telah di-generate.')
                ->success()
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error Generate Dokumen')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
        dispatch(new EmailStatusPengajuan(id: $record->karyawan_id));
        dispatch(new EmailPengajuanKeAdmin($record->karyawan_id));
    }
}
