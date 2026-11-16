<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Konversi data teks lama (misal "November 2026") ke format tanggal valid sebelum alter column
        DB::table('tugas_bulanan')
            ->whereRaw("bulan_target NOT REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$'")
            ->update(['bulan_target' => now()->endOfMonth()->toDateString()]);

        Schema::table('tugas_bulanan', function (Blueprint $table) {
            $table->date('bulan_target')->change();
        });
    }

    public function down(): void
    {
        Schema::table('tugas_bulanan', function (Blueprint $table) {
            $table->string('bulan_target', 20)->change();
        });
    }
};