<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('d_cuti', function (Blueprint $table) {
            $table->string('signature_karyawan')->nullable()->after('keterangan');
            $table->string('signature_direktur')->nullable()->after('approved_by');
        });
    }

    public function down(): void
    {
        Schema::table('d_cuti', function (Blueprint $table) {
            $table->dropColumn(['signature_karyawan', 'signature_direktur']);
        });
    }
};
