<?php

namespace App\Services;

use App\Models\Pegawai;

class StatistikPegawaiService
{
    public function getStatistikGender(): array
    {
        $pegawais = Pegawai::all();

        return [
            'Laki-laki' => (int) $pegawais->where('jenis_kelamin', 'Laki-laki')->count(),
            'Perempuan' => (int) $pegawais->where('jenis_kelamin', 'Perempuan')->count(),
        ];
    }

    public function getStatistikUmur(): array
    {
        $pegawais = Pegawai::all();

        return [
            '18-20 Tahun' => (int) $pegawais->filter(fn ($p) => $p->umur >= 18 && $p->umur <= 20)->count(),
            '21-25 Tahun' => (int) $pegawais->filter(fn ($p) => $p->umur >= 21 && $p->umur <= 25)->count(),
            '26-30 Tahun' => (int) $pegawais->filter(fn ($p) => $p->umur >= 26 && $p->umur <= 30)->count(),
            '31-40 Tahun' => (int) $pegawais->filter(fn ($p) => $p->umur >= 31 && $p->umur <= 40)->count(),
            '> 40 Tahun'  => (int) $pegawais->filter(fn ($p) => $p->umur > 40)->count(),
        ];
    }

    public function getStatistikPendidikan(): array
    {
        $pegawais = Pegawai::all();

        return [
            'SMA/SMK' => (int) $pegawais->where('pendidikan_terakhir', 'SMA/SMK')->count(),
            'D3'      => (int) $pegawais->where('pendidikan_terakhir', 'D3')->count(),
            'S1'      => (int) $pegawais->where('pendidikan_terakhir', 'S1')->count(),
            'S2'      => (int) $pegawais->where('pendidikan_terakhir', 'S2')->count(),
        ];
    }

    public function getRataRataUmur(): float
    {
        $total = Pegawai::count();

        return $total > 0 ? round((float) Pegawai::avg('umur'), 1) : 0;
    }
}