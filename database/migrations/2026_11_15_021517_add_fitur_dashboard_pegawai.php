<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom lokasi (WFO/WFH) pada tabel absensi
        Schema::table('absensi', function (Blueprint $table) {
            if (!Schema::hasColumn('absensi', 'lokasi')) {
                $table->enum('lokasi', ['WFO', 'WFH'])->default('WFO')->after('jam_keluar');
            }
        });

        // 2. Tabel Klaim Struk (untuk Quick Action & sisa Wellness Budget)
        Schema::create('klaim_struk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
            $table->string('kategori')->default('Wellness');
            $table->string('keterangan');
            $table->unsignedBigInteger('nominal');
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->timestamps();
        });

        // 3. Tabel Kudos (Apresiasi antar rekan tim)
        Schema::create('kudos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengirim_id')->constrained('pegawai')->cascadeOnDelete();
            $table->foreignId('penerima_id')->constrained('pegawai')->cascadeOnDelete();
            $table->string('pesan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kudos');
        Schema::dropIfExists('klaim_struk');
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropColumn('lokasi');
        });
    }
};