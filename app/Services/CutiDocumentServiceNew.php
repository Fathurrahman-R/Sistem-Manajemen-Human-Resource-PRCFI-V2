<?php

namespace App\Services;

use App\Models\Cuti;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\TemplateProcessor;

class CutiDocumentServiceNew
{
    /**
     * Simpan signature ke storage dan return path
     */
    public function saveSignature(string $signatureData, string $type = 'karyawan'): string
    {
        if (str_starts_with($signatureData, 'data:image')) {
            // Base64 data URL dari canvas draw
            [$meta, $content] = explode(',', $signatureData, 2);
            $binary = base64_decode($content);

            $filename = "signature_{$type}_" . Str::uuid() . '.png';
            $path = "signatures/{$filename}";

            Storage::disk('public')->put($path, $binary);
            return $path;
        } else {
            // File upload - sudah di signatures/ folder (dari FileUpload directory config)
            // Cek apakah path sudah benar
            if (str_starts_with($signatureData, 'signatures/')) {
                // Path sudah benar
                return $signatureData;
            }

            // Jika masih di temp-signatures atau path lain, pindahkan
            if (Storage::disk('public')->exists($signatureData)) {
                $filename = "signature_{$type}_" . Str::uuid() . '.png';
                $newPath = "signatures/{$filename}";

                $content = Storage::disk('public')->get($signatureData);
                Storage::disk('public')->put($newPath, $content);
                Storage::disk('public')->delete($signatureData);

                return $newPath;
            }

            throw new \Exception("Signature file not found: {$signatureData}");
        }
    }

    /**
     * Generate dokumen cuti dengan signature yang sudah disimpan di database
     * Setelah dokumen di-generate, hapus signature dari storage dan database
     */
    public function generateAndSaveCutiDocument(Cuti $cuti): string
    {
        $templatePath = storage_path('app/templates/surat_cuti_template.docx');

        if (!file_exists($templatePath)) {
            throw new \Exception("Template surat cuti tidak ditemukan di: {$templatePath}");
        }

        $templateProcessor = new TemplateProcessor($templatePath);

        // Replace placeholders
        $templateProcessor->setValue('karyawan_nama', $cuti->karyawan->nama_lengkap ?? '-');
        $templateProcessor->setValue('karyawan_posisi', $cuti->karyawan->posisi ?? '-');
        $templateProcessor->setValue('unit_kerja', $cuti->karyawan->unit_kerja ?? '-');
        $templateProcessor->setValue('tempat_lahir', $cuti->karyawan->tempat_lahir ?? '-');

        $tanggalLahir = $cuti->karyawan->tanggal_lahir ? $cuti->karyawan->tanggal_lahir->format('d F Y') : '-';
        $templateProcessor->setValue('tanggal_lahir', $tanggalLahir);
        $templateProcessor->setValue('tanggal _lahir', $tanggalLahir); // dengan spasi

        $templateProcessor->setValue('tempat_dibuat', $cuti->tempat_dibuat);

        $tanggalDibuat = $cuti->tanggal_dibuat->format('d F Y');
        $templateProcessor->setValue('tanggal_dibuat', $tanggalDibuat);
        $templateProcessor->setValue('tanggal_dibuat', $tanggalDibuat); // dengan spasi

        $templateProcessor->setValue('tanggal_mulai', $cuti->tanggal_mulai->format('d F Y'));
        $templateProcessor->setValue('tanggal_selesai', $cuti->tanggal_selesai->format('d F Y'));

        $lamaCuti = $cuti->tanggal_mulai->diffInDays($cuti->tanggal_selesai) + 1;
        $templateProcessor->setValue('lama_cuti', $lamaCuti . " hari ");

        $templateProcessor->setValue('keterangan', $cuti->keterangan);
        $templateProcessor->setValue('jumlah_lampiran', $cuti->jumlah_lampiran !== 0 ? $cuti->jumlah_lampiran : '-');

//        $templateProcessor->setValue('approved_at', $cuti->approved_at ?? '-');
//        $templateProcessor->setValue('approved_date', $cuti->approved_date ? \Carbon\Carbon::parse($cuti->approved_date)->format('d F Y') : '-');
//        $templateProcessor->setValue('approved_by', $cuti->approved_by ?? '-');

        // Handle signature karyawan dari database
        if ($cuti->signature_karyawan && Storage::disk('public')->exists($cuti->signature_karyawan)) {
            $signaturePath = Storage::disk('public')->path($cuti->signature_karyawan);
            \Log::info('Trying to set karyawan signature', [
                'path' => $signaturePath,
                'exists' => file_exists($signaturePath),
                'size' => file_exists($signaturePath) ? filesize($signaturePath) : 0,
                'db_path' => $cuti->signature_karyawan
            ]);

            try {
                $templateProcessor->setImageValue('signature', [
                    'path' => $signaturePath,
                    'width' => 150,
                    'height' => 75,
                    'ratio' => false,
                ]);
                \Log::info('Karyawan signature set successfully');
            } catch (\Exception $e) {
                \Log::error('Failed to set karyawan signature (possibly in table cell)', ['error' => $e->getMessage()]);
                // Fallback: gunakan text jika tidak bisa bind image (karena di dalam tabel)
                $templateProcessor->setValue('signature', '[TANDA TANGAN KARYAWAN]');
            }
        } else {
            \Log::warning('Karyawan signature not found', [
                'db_path' => $cuti->signature_karyawan,
                'exists_in_storage' => $cuti->signature_karyawan ? Storage::disk('public')->exists($cuti->signature_karyawan) : false
            ]);
            $templateProcessor->setValue('signature', '');
        }

        // Handle signature direktur dari database (jika sudah ada)
//        if ($cuti->signature_direktur && Storage::disk('public')->exists($cuti->signature_direktur)) {
//            $signaturePath = Storage::disk('public')->path($cuti->signature_direktur);
//            \Log::info('Trying to set direktur signature', [
//                'path' => $signaturePath,
//                'exists' => file_exists($signaturePath),
//                'size' => file_exists($signaturePath) ? filesize($signaturePath) : 0,
//                'db_path' => $cuti->signature_direktur
//            ]);
//
//            try {
//                $templateProcessor->setImageValue('signature_direktur', [
//                    'path' => $signaturePath,
//                    'width' => 150,
//                    'height' => 75,
//                    'ratio' => false,
//                ]);
//                \Log::info('Direktur signature set successfully');
//            } catch (\Exception $e) {
//                \Log::error('Failed to set direktur signature (possibly in table cell)', ['error' => $e->getMessage()]);
//                // Fallback: gunakan text jika tidak bisa bind image (karena di dalam tabel)
//                $templateProcessor->setValue('signature_direktur', '[TANDA TANGAN DIREKTUR]');
//            }
//        } else {
//            $templateProcessor->setValue('signature_direktur', '');
//        }

        // Generate filename
        $namaKaryawan = str_replace(' ', '_', $cuti->karyawan->nama_lengkap ?? 'Unknown');
        $keterangan = str_replace(' ', '_', substr($cuti->keterangan, 0, 30));
        $tanggalDiajukan = $cuti->tanggal_dibuat->format('Ymd');
        $filename = "Surat_Cuti_{$namaKaryawan}_{$keterangan}_{$tanggalDiajukan}.docx";

        $outputPath = storage_path('app/public/documents/cuti/' . $filename);

        if (!file_exists(dirname($outputPath))) {
            mkdir(dirname($outputPath), 0755, true);
        }

        // Save dokumen
        $templateProcessor->saveAs($outputPath);

        // TIDAK hapus signature di sini - biarkan scheduler yang handle cleanup
        // Ini memastikan file masih ada saat PHPWord memproses dokumen
        // Signature akan dihapus oleh scheduler saat dokumen expired

        return 'documents/cuti/' . $filename;
    }

    /**
     * Hapus signature files dari storage DAN hapus path dari database
     */
    public function cleanupSignatures(Cuti $cuti): void
    {
        // Hapus dari storage
        if ($cuti->signature_karyawan && Storage::disk('public')->exists($cuti->signature_karyawan)) {
            Storage::disk('public')->delete($cuti->signature_karyawan);
        }
        if ($cuti->signature_direktur && Storage::disk('public')->exists($cuti->signature_direktur)) {
            Storage::disk('public')->delete($cuti->signature_direktur);
        }

        // Hapus path dari database
        $cuti->update([
            'signature_karyawan' => null,
            'signature_direktur' => null,
        ]);
    }

    /**
     * Update dokumen existing dengan approval dan signature direktur dari database
     * Setelah update, hapus signature dari storage dan database
     */
    public function updateDocumentWithApproval(Cuti $cuti): void
    {
        if (!$cuti->file_path || !Storage::disk('public')->exists($cuti->file_path)) {
            throw new \Exception("Dokumen pengajuan tidak ditemukan.");
        }

        $documentPath = Storage::disk('public')->path($cuti->file_path);
        $templateProcessor = new TemplateProcessor($documentPath);

        // Update approval info
        $templateProcessor->setValue('approved_at', $cuti->approved_at ?? '-');
        $templateProcessor->setValue('approved_date', $cuti->approved_date ? \Carbon\Carbon::parse($cuti->approved_date)->format('d F Y') : '-');
        $templateProcessor->setValue('approved_by', $cuti->approved_by ?? '-');

        // Handle signature direktur dari database
        if ($cuti->signature_direktur && Storage::disk('public')->exists($cuti->signature_direktur)) {
            $signaturePath = Storage::disk('public')->path($cuti->signature_direktur);
            \Log::info('Updating document with direktur signature', [
                'path' => $signaturePath,
                'exists' => file_exists($signaturePath),
                'size' => file_exists($signaturePath) ? filesize($signaturePath) : 0,
                'db_path' => $cuti->signature_direktur
            ]);

            try {
                $templateProcessor->setImageValue('signature_direktur', [
                    'path' => $signaturePath,
                    'width' => 150,
                    'height' => 75,
                    'ratio' => false,
                ]);
                \Log::info('Direktur signature updated successfully');
            } catch (\Exception $e) {
                \Log::error('Failed to set direktur signature on approval (possibly in table cell)', ['error' => $e->getMessage()]);
                // Fallback: gunakan text jika tidak bisa bind image (karena di dalam tabel)
                $templateProcessor->setValue('signature_direktur', '[TANDA TANGAN DIREKTUR]');
            }
        } else {
            \Log::warning('Direktur signature not found for update', [
                'db_path' => $cuti->signature_direktur,
                'exists_in_storage' => $cuti->signature_direktur ? Storage::disk('public')->exists($cuti->signature_direktur) : false
            ]);
            $templateProcessor->setValue('signature_direktur', '');
        }

        // Save dokumen
        $templateProcessor->saveAs($documentPath);

        // TIDAK hapus signature di sini - biarkan scheduler yang handle
        // Signature akan dihapus bersamaan dengan dokumen saat expired
    }

    /**
     * Cleanup dokumen expired (30 hari setelah tanggal selesai)
     * Juga hapus signature files yang terkait
     */
    public function cleanupExpiredDocuments(): int
    {
        $deletedCount = 0;
        $expiredCutis = \App\Models\Cuti::whereNotNull('file_path')
            ->where('tanggal_selesai', '<', now()->subDays(30))
            ->get();

        foreach ($expiredCutis as $cuti) {
            // Hapus dokumen
            if ($cuti->file_path && Storage::disk('public')->exists($cuti->file_path)) {
                Storage::disk('public')->delete($cuti->file_path);
                $deletedCount++;
            }

            // Hapus signature files
            if ($cuti->signature_karyawan && Storage::disk('public')->exists($cuti->signature_karyawan)) {
                Storage::disk('public')->delete($cuti->signature_karyawan);
            }
            if ($cuti->signature_direktur && Storage::disk('public')->exists($cuti->signature_direktur)) {
                Storage::disk('public')->delete($cuti->signature_direktur);
            }

            // Update database
            $cuti->update([
                'file_path' => null,
                'signature_karyawan' => null,
                'signature_direktur' => null,
            ]);
        }

        return $deletedCount;
    }
    public function cleanupAllDocuments(Cuti $record, ?string $documentPath, ?string $karyawanSignature, ?string $direkturSignature, ?array $lampiran):void
    {
        if ($documentPath && Storage::disk('public')->exists($documentPath)) {
            Storage::disk('public')->delete($documentPath);
        }
        if ($karyawanSignature && Storage::disk('public')->exists($karyawanSignature)) {
            Storage::disk('public')->delete($karyawanSignature);
        }
        if ($direkturSignature && Storage::disk('public')->exists($direkturSignature)) {
            Storage::disk('public')->delete($direkturSignature);
        }
        if ($lampiran)
        {
            foreach ($lampiran as $path) {
                if ($path && Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }
        $record->update([
            'file_path' => null,
            'signature_karyawan' => null,
            'signature_direktur' => null,
            'lampiran' => null,
        ]);
    }
}

