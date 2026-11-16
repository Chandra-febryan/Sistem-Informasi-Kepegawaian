@extends('layouts.app')
@section('title', 'Dashboard Admin — HR Startup')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header Halaman -->
    <div class="pb-5 border-b border-zinc-200 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <span class="text-[11px] font-mono uppercase tracking-wider text-zinc-400">Administrasi & Operasional</span>
            <h1 class="text-2xl font-semibold tracking-tight text-zinc-900 mt-0.5">Ringkasan Kepegawaian</h1>
        </div>

        <a href="{{ route('pegawai.create') }}"
           class="bg-emerald-700 hover:bg-emerald-800 text-white px-4 py-2 rounded-md text-xs font-semibold transition-colors self-start sm:self-auto">
            + Tambah Pegawai
        </a>
    </div>

    <!-- 1. RINGKASAN TOTAL KARYAWAN (Di Atas Akses Cepat) -->
    <div class="mt-6 grid grid-cols-2 lg:grid-cols-4 border border-zinc-200 rounded-lg bg-white divide-y lg:divide-y-0 lg:divide-x divide-zinc-200 overflow-hidden">
        <div class="p-4 sm:p-5">
            <span class="text-xs text-zinc-500 block">Total Karyawan</span>
            <div class="mt-2 flex items-baseline gap-1.5 tabular-nums">
                <span class="text-2xl font-semibold text-zinc-900">{{ $totalPegawai }}</span>
                <span class="text-xs text-zinc-400">orang</span>
            </div>
        </div>

        <div class="p-4 sm:p-5">
            <span class="text-xs text-zinc-500 block">Laki-laki</span>
            <div class="mt-2 flex items-baseline gap-1.5 tabular-nums">
                <span class="text-2xl font-semibold text-zinc-900">{{ $statistikGender['Laki-laki'] ?? 0 }}</span>
                <span class="text-xs text-zinc-400">
                    ({{ $totalPegawai > 0 ? round((($statistikGender['Laki-laki'] ?? 0) / $totalPegawai) * 100) : 0 }}%)
                </span>
            </div>
        </div>

        <div class="p-4 sm:p-5">
            <span class="text-xs text-zinc-500 block">Perempuan</span>
            <div class="mt-2 flex items-baseline gap-1.5 tabular-nums">
                <span class="text-2xl font-semibold text-zinc-900">{{ $statistikGender['Perempuan'] ?? 0 }}</span>
                <span class="text-xs text-zinc-400">
                    ({{ $totalPegawai > 0 ? round((($statistikGender['Perempuan'] ?? 0) / $totalPegawai) * 100) : 0 }}%)
                </span>
            </div>
        </div>

        <div class="p-4 sm:p-5">
            <span class="text-xs text-zinc-500 block">Rata-rata Usia</span>
            <div class="mt-2 flex items-baseline gap-1.5 tabular-nums">
                <span class="text-2xl font-semibold text-emerald-700">{{ $rataRataUmur ?? 0 }}</span>
                <span class="text-xs text-zinc-400">tahun</span>
            </div>
        </div>
    </div>

    <!-- 2. AKSES CEPAT MODUL -->
    <div class="mt-6">
        <h2 class="text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-2.5">Akses Cepat Modul</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 border border-zinc-200 rounded-lg bg-white divide-y sm:divide-y-0 sm:divide-x divide-zinc-200 overflow-hidden">
            <a href="{{ route('pegawai.index') }}"
               class="group p-4 hover:bg-zinc-50 transition-colors flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <span class="block text-xs font-semibold text-zinc-900 group-hover:text-emerald-700">Data Pegawai</span>
                    <span class="block text-[11px] text-zinc-500 mt-0.5 truncate">{{ $totalPegawai }} akun terdaftar</span>
                </div>
                <span class="font-mono text-xs text-zinc-400 group-hover:text-emerald-700">&rarr;</span>
            </a>

            <a href="{{ route('admin.absensi') }}"
               class="group p-4 hover:bg-zinc-50 transition-colors flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <span class="block text-xs font-semibold text-zinc-900 group-hover:text-emerald-700">Absensi</span>
                    <span class="block text-[11px] text-zinc-500 mt-0.5 truncate">Pantau presensi harian</span>
                </div>
                <span class="font-mono text-xs text-zinc-400 group-hover:text-emerald-700">&rarr;</span>
            </a>

            <a href="{{ route('admin.cuti') }}"
               class="group p-4 hover:bg-zinc-50 transition-colors flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <span class="block text-xs font-semibold text-zinc-900 group-hover:text-emerald-700">Ajuan Cuti</span>
                    <span class="block text-[11px] text-zinc-500 mt-0.5 truncate">Persetujuan izin & cuti</span>
                </div>
                <span class="font-mono text-xs text-zinc-400 group-hover:text-emerald-700">&rarr;</span>
            </a>

            <a href="{{ route('admin.slip-gaji') }}"
               class="group p-4 hover:bg-zinc-50 transition-colors flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <span class="block text-xs font-semibold text-zinc-900 group-hover:text-emerald-700">Slip Gaji</span>
                    <span class="block text-[11px] text-zinc-500 mt-0.5 truncate">Penerbitan payroll</span>
                </div>
                <span class="font-mono text-xs text-zinc-400 group-hover:text-emerald-700">&rarr;</span>
            </a>

            <a href="{{ route('admin.struk') }}"
               class="group p-4 hover:bg-zinc-50 transition-colors flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <span class="block text-xs font-semibold text-zinc-900 group-hover:text-emerald-700">Klaim Struk</span>
                    <span class="block text-[11px] text-zinc-500 mt-0.5 truncate">Verifikasi pembayaran</span>
                </div>
                <span class="font-mono text-xs text-zinc-400 group-hover:text-emerald-700">&rarr;</span>
            </a>
        </div>
    </div>

    <!-- 3. BENTO GRID STATISTIK DEMOGRAFI PEGAWAI -->
    <div class="mt-6 grid grid-cols-1 lg:grid-cols-12 gap-5">
        <!-- Chart 1: Rentang Usia (18-20, 21-25, 26-30, 31-40, > 40 Tahun) -->
        <div class="lg:col-span-7 border border-zinc-200 rounded-lg bg-white p-5 flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-4">
                <div>
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-zinc-600">Distribusi Rentang Usia</h2>
                    <p class="text-[11px] text-zinc-400 mt-0.5">Kelompok usia karyawan aktif (18–20 thn s/d &gt; 40 thn)</p>
                </div>
                <span class="text-[11px] font-mono text-zinc-400">USIA</span>
            </div>
            <div class="h-60">
                <canvas id="chartUmur"></canvas>
            </div>
        </div>

        <!-- Chart 2: Komposisi Gender (Laki-laki & Perempuan) -->
        <div class="lg:col-span-5 border border-zinc-200 rounded-lg bg-white p-5 flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-4">
                <div>
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-zinc-600">Komposisi Gender</h2>
                    <p class="text-[11px] text-zinc-400 mt-0.5">Perbandingan Laki-laki dan Perempuan</p>
                </div>
                <span class="text-[11px] font-mono text-zinc-400">GENDER</span>
            </div>
            <div class="h-44 flex items-center justify-center">
                <canvas id="chartGender"></canvas>
            </div>
            <!-- Ringkasan Angka Gender -->
            <div class="mt-4 pt-3 border-t border-zinc-100 grid grid-cols-2 gap-3 text-xs tabular-nums">
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center gap-1.5 text-zinc-600">
                        <span class="w-2.5 h-2.5 rounded-xs bg-zinc-900"></span> Laki-laki
                    </span>
                    <strong class="font-semibold text-zinc-900">{{ $statistikGender['Laki-laki'] ?? 0 }}</strong>
                </div>
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center gap-1.5 text-zinc-600">
                        <span class="w-2.5 h-2.5 rounded-xs bg-emerald-700"></span> Perempuan
                    </span>
                    <strong class="font-semibold text-zinc-900">{{ $statistikGender['Perempuan'] ?? 0 }}</strong>
                </div>
            </div>
        </div>

        <!-- Chart 3: Jenjang Pendidikan (SMA/SMK, D3, S1, S2) -->
        <div class="lg:col-span-12 border border-zinc-200 rounded-lg bg-white p-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-zinc-100 mb-4">
                <div>
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-zinc-600">Jenjang Pendidikan Terakhir</h2>
                    <p class="text-[11px] text-zinc-400 mt-0.5">Sebaran kualifikasi pendidikan (SMA/SMK, D3, S1, S2)</p>
                </div>

                <!-- Ringkasan Angka Per Jenjang -->
                <div class="flex flex-wrap items-center gap-3 text-[11px] font-mono text-zinc-600 tabular-nums">
                    @foreach($statistikPendidikan as $jenjang => $jumlah)
                        <span class="px-2 py-0.5 rounded bg-zinc-100 border border-zinc-200">
                            {{ $jenjang }}: <strong class="text-zinc-900">{{ is_array($jumlah) ? count($jumlah) : $jumlah }}</strong>
                        </span>
                    @endforeach
                </div>
            </div>
            <div class="h-52">
                <canvas id="chartPendidikan"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof window.Chart === 'undefined') return;

        const gridColor = '#f4f4f5';
        const textColor = '#71717a';

        // 1. Horizontal Bar Chart: Rentang Usia
        new window.Chart(document.getElementById('chartUmur'), {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($statistikUmur)) !!},
                datasets: [{
                    label: 'Jumlah Karyawan',
                    data: {!! json_encode(array_values($statistikUmur)) !!},
                    backgroundColor: '#047857',
                    borderRadius: 4,
                    maxBarThickness: 26
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: textColor, font: { size: 11 } },
                        grid: { color: gridColor }
                    },
                    y: {
                        ticks: { color: '#18181b', font: { size: 11, weight: '500' } },
                        grid: { display: false }
                    }
                }
            }
        });

        // 2. Doughnut Chart: Gender (Laki-laki & Perempuan)
        new window.Chart(document.getElementById('chartGender'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_keys($statistikGender)) !!},
                datasets: [{
                    data: {!! json_encode(array_values($statistikGender)) !!},
                    backgroundColor: ['#18181b', '#047857'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // 3. Bar Chart: Jenjang Pendidikan (SMA/SMK, D3, S1, S2)
        new window.Chart(document.getElementById('chartPendidikan'), {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($statistikPendidikan)) !!},
                datasets: [{
                    label: 'Jumlah Karyawan',
                    data: {!! json_encode(array_values($statistikPendidikan)) !!},
                    backgroundColor: ['#52525b', '#3f3f46', '#047857', '#18181b'],
                    borderRadius: 4,
                    maxBarThickness: 48
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: textColor, font: { size: 11 } },
                        grid: { color: gridColor }
                    },
                    x: {
                        ticks: { color: '#18181b', font: { size: 12, weight: '600' } },
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endsection