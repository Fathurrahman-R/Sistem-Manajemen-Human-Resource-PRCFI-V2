<?php

namespace App\Console\Commands;

use App\Services\CutiDocumentServiceNew;
use Illuminate\Console\Command;

class CleanupExpiredCutiDocuments extends Command
{
    protected $signature = 'cuti:cleanup-documents';
    protected $description = 'Hapus dokumen pengajuan cuti yang sudah lewat 30 hari dari tanggal selesai';

    public function handle(CutiDocumentServiceNew $documentService): int
    {
        $this->info('Memulai cleanup dokumen cuti yang expired...');

        $deletedCount = $documentService->cleanupExpiredDocuments();

        if ($deletedCount > 0) {
            $this->info("✓ Berhasil menghapus {$deletedCount} dokumen cuti yang expired.");
        } else {
            $this->info('✓ Tidak ada dokumen cuti yang perlu dihapus.');
        }

        return Command::SUCCESS;
    }
}
