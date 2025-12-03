<?php

use App\Enum\Master\EnglishSkill;
use App\Enum\Master\RiwayatPendidikan;
use App\Enum\Master\StatusKerja;
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
        Schema::create('m_karyawan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap',100)->nullable(false);
            $table->string('posisi',100)->nullable(false);
            $table->string('tempat_lahir',100)->nullable(false);
            $table->date('tanggal_lahir')->nullable(false);
            $table->string('email',100)->unique()->nullable(false);
            $table->enum('jenis_kelamin',['Laki-laki','Perempuan'])->nullable(false);
            $table->enum('riwayat_pendidikan', RiwayatPendidikan::cases())->nullable()->default(null);
            $table->string('institusi_pendidikan')->nullable()->default(null);
            $table->enum('english_skill', EnglishSkill::cases())->nullable()->default(null);
            $table->integer('pengalaman_kerja')->nullable()->default(0);
            $table->date('tanggal_bergabung');
            $table->date('tanggal_expired')->nullable()->default(null);
            $table->integer('masa_kerja')->nullable()->default(0);
            $table->enum('status', StatusKerja::cases())->default(StatusKerja::Kontrak->value);
            $table->string('cv')->nullable()->default(null);
            $table->string('ktp')->nullable()->default(null);
            $table->string('kk')->nullable()->default(null);
            $table->string('npwp',25)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
