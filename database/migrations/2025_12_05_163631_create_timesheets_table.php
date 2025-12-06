<?php

use App\Enum\Timesheet\Location;
use App\Enum\Timesheet\StatusPersetujuan;
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
        Schema::create('d_timesheet', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->nullable(false)->constrained('m_karyawan')->cascadeOnDelete()->cascadeOnUpdate();
            $table->date('tanggal')->nullable(false)->default(date('Y-m-01'))->index();
            $table->enum('status', array_column(StatusPersetujuan::cases(), 'value'))->nullable(false)->default(StatusPersetujuan::Dibuat->value)->index();
            $table->string('path_kehadiran')->nullable()->default(null)->index();
            $table->string('path_aktifitas')->nullable()->default(null)->index();
            $table->timestamps();
        });

        Schema::create('d_isi_timesheet', function (Blueprint $table) {
            $table->id();
            $table->foreignId('timesheet_id')->nullable(false)->constrained('d_timesheet')->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('tanggal')->nullable(false)->default(now())->index();
            $table->integer('jam_bekerja')->nullable(false)->default(0);
            $table->enum('location', array_column(Location::cases(), 'value'))->nullable(false)->default(Location::Pontianak->value)->index();
            $table->string('place',100)->nullable()->index();
            $table->string('work_done')->nullable();
            $table->timestamps();
        });

        Schema::create('timesheet_cuti', function (Blueprint $table) {
            $table->foreignId('timesheet_id')->nullable()->constrained('m_karyawan')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('cuti_id')->nullable()->constrained('d_cuti')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('d_timesheet');
        Schema::dropIfExists('d_isi_timesheet');
        Schema::dropIfExists('timesheet_cuti');
    }
};
