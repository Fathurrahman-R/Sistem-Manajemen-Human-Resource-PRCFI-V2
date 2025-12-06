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
        Schema::table('d_timesheet', function (Blueprint $table) {
            $table->string('signature_karyawan')->nullable()->after('karyawan_id');
            $table->string('signature_direktur')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('d_timesheet', function (Blueprint $table) {
            $table->dropColumn('signature_karyawan');
            $table->dropColumn('signature_direktur');
        });
    }
};
