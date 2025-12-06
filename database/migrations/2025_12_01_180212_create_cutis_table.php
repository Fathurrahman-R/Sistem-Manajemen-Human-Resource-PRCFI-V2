<?php

use App\Enum\Cuti\StatusPengajuan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('d_cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('m_karyawan','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('tempat_dibuat',100)->nullable(false);
            $table->date('tanggal_dibuat')->nullable(false)->default(now());
            $table->date('tanggal_mulai')->nullable(false);
            $table->date('tanggal_selesai')->nullable(false);
            $table->text('keterangan')->nullable(false);
            $table->enum('status', array_column(StatusPengajuan::cases(), 'value'))->nullable(false)->default(StatusPengajuan::Diajukan->value);
            $table->string('approved_at',100)->nullable()->default(null);
            $table->date('approved_date')->nullable()->default(null);
            $table->string('approved_by',100)->nullable()->default(null);
            $table->text('file_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('d_cuti');
    }
};
