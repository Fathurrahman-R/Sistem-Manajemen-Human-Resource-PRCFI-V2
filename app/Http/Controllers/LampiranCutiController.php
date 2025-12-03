<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LampiranCutiController extends Controller
{
    /**
     * Download lampiran cuti
     */
    public function download(Cuti $cuti, string $filename): StreamedResponse
    {
        // Authorization check - hanya yang bisa view cuti yang bisa download lampiran
        Gate::authorize('view', $cuti);

        // Cek apakah file ada di lampiran
        $lampiran = $cuti->lampiran ?? [];
        $filePath = null;

        foreach ($lampiran as $file) {
            if (basename($file) === $filename) {
                $filePath = $file;
                break;
            }
        }

        if (!$filePath || !Storage::exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        // Stream download file
        return Storage::download($filePath, $filename);
    }
}
