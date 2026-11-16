<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Data Master Pegawai (One-to-One dengan users)
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('kode_pegawai')->unique();
            $table->string('nama_lengkap');
            $table->string('alamat');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->unsignedTinyInteger('umur');
            $table->string('pendidikan_terakhir');
            $table->string('jabatan')->default('Staff');
            $table->timestamps();
        });

        // 2. Absensi Harian
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('jam_masuk');
            $table->time('jam_keluar')->nullable();
            $table->enum('status', ['hadir', 'terlambat'])->default('hadir');
            $table->timestamps();
        });

        // 3. Pengajuan Cuti
        Schema::create('cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->text('keterangan');
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->timestamps();
        });

        // 4. Slip Gaji Bulanan
        Schema::create('slip_gaji', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
            $table->string('periode_bulan'); // Format: "2026-11"
            $table->unsignedBigInteger('gaji_pokok');
            $table->unsignedBigInteger('tunjangan')->default(0);
            $table->unsignedBigInteger('potongan')->default(0);
            $table->unsignedBigInteger('total_gaji');
            $table->timestamps();
        });

        // 5. Daftar Tugas Bulanan
        Schema::create('tugas_bulanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
            $table->string('judul_tugas');
            $table->text('deskripsi')->nullable();
            $table->string('bulan_target'); // Format: "November 2026"
            $table->enum('status', ['todo', 'in_progress', 'done'])->default('todo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas_bulanan');
        Schema::dropIfExists('slip_gaji');
        Schema::dropIfExists('cuti');
        Schema::dropIfExists('absensi');
        Schema::dropIfExists('pegawai');
    }
};