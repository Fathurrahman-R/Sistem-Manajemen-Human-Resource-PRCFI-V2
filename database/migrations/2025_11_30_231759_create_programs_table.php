<?php

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
        Schema::create('m_program', function (Blueprint $table) {
            $table->id();
            $table->string('nama',100)->unique()->nullable(false);
            $table->string('lokasi',100)->nullable(false);
            $table->date('tanggal_mulai')->nullable(false)->default(now());
            $table->date('tanggal_selesai')->nullable(false);
            $table->timestamps();
        });

        Schema::create('karyawan_program', function (Blueprint $table) {
            $table->foreignId('program_id')->constrained('m_program')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('karyawan_id')->constrained('m_karyawan')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_program');
        Schema::dropIfExists('karyawan_program');
    }
};
