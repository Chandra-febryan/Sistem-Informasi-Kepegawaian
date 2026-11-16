@extends('layouts.app')
@section('title', 'Ringkasan Kerja — ' . $pegawai->nama_lengkap)

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header Identitas & Tanggal -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 pb-6 border-b border-zinc-200">
        <div>
            <div class="flex items-center gap-2 text-xs font-mono text-zinc-500 mb-1">
                <span>{{ $pegawai->kode_pegawai }}</span>
                <span>/</span>
                <span>{{ $pegawai->jabatan }}</span>
            </div>
            <h1 class="text-2xl font-semibold tracking-tight text-zinc-900">
                {{ $pegawai->nama_lengkap }}
            </h1>
        </div>

        <div class="flex items-center gap-3">
            <span class="text-xs font-medium text-zinc-600 bg-white px-3 py-1.5 rounded-md border border-zinc-200 tabular-nums">
                {{ now()->translatedFormat('l, d M Y') }}
            </span>
        </div>
    </div>

    <!-- Bar Navigasi Utama: Absensi, Ajuan Cuti, Slip Gaji (Direct Form Links) -->
    <div class="mt-6">
        <div class="flex items-center justify-between mb-2.5">
            <h2 class="text-xs font-semibold uppercase tracking-wider text-zinc-500">Akses Form Utama</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 border border-zinc-200 rounded-lg bg-white divide-y md:divide-y-0 md:divide-x divide-zinc-200 overflow-hidden">
            <!-- Bar 1: Form Absensi -->
            <a href="{{ route('pegawai.absensi.index') }}"
               class="group p-4 sm:p-5 hover:bg-zinc-50 transition-colors flex items-center justify-between gap-4">
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-semibold text-zinc-900 group-hover:text-emerald-700 transition-colors">
                            Absensi
                        </span>
                        @if(!$absensiHariIni)
                            <span class="text-[11px] font-medium px-2 py-0.5 rounded bg-zinc-100 text-zinc-600">
                                Belum Masuk
                            </span>
                        @elseif(!$absensiHariIni->jam_keluar)
                            <span class="text-[11px] font-medium px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200 tabular-nums">
                                {{ $absensiHariIni->lokasi ?? 'WFO' }} · {{ $absensiHariIni->jam_masuk }}
                            </span>
                        @else
                            <span class="text-[11px] font-medium px-2 py-0.5 rounded bg-zinc-100 text-zinc-700 tabular-nums">
                                Selesai ({{ $absensiHariIni->jam_keluar }})
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-zinc-500 mt-1 truncate">
                        Buka form pencatatan hadir & riwayat presensi
                    </p>
                </div>
                <span class="text-sm font-mono text-zinc-400 group-hover:text-emerald-700 group-hover:translate-x-0.5 transition-all shrink-0">
                    &rarr;
                </span>
            </a>

            <!-- Bar 2: Form Ajuan Cuti -->
            <a href="{{ route('pegawai.cuti.index') }}"
               class="group p-4 sm:p-5 hover:bg-zinc-50 transition-colors flex items-center justify-between gap-4">
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-semibold text-zinc-900 group-hover:text-emerald-700 transition-colors">
                            Ajuan Cuti
                        </span>
                        @if($jumlahCutiPending > 0)
                            <span class="text-[11px] font-medium px-2 py-0.5 rounded bg-zinc-900 text-white tabular-nums">
                                {{ $jumlahCutiPending }} Menunggu
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-zinc-500 mt-1 truncate">
                        Buat permohonan cuti & cek status persetujuan
                    </p>
                </div>
                <span class="text-sm font-mono text-zinc-400 group-hover:text-emerald-700 group-hover:translate-x-0.5 transition-all shrink-0">
                    &rarr;
                </span>
            </a>

            <!-- Bar 3: Slip Gaji -->
            <a href="{{ route('pegawai.slip-gaji.index') }}"
               class="group p-4 sm:p-5 hover:bg-zinc-50 transition-colors flex items-center justify-between gap-4">
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-semibold text-zinc-900 group-hover:text-emerald-700 transition-colors">
                            Slip Gaji
                        </span>
                        @if($slipTerbaru)
                            <span class="text-[11px] font-mono px-2 py-0.5 rounded bg-zinc-100 text-zinc-700">
                                {{ $slipTerbaru->periode_bulan }}
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-zinc-500 mt-1 truncate">
                        Rincian gaji pokok, tunjangan & potongan
                    </p>
                </div>
                <span class="text-sm font-mono text-zinc-400 group-hover:text-emerald-700 group-hover:translate-x-0.5 transition-all shrink-0">
                    &rarr;
                </span>
            </a>
        </div>
    </div>

    <!-- Bento Grid Asimetris: Ringkasan Saldo & Target + Info Tim Hari Ini -->
    <div class="mt-6 grid grid-cols-1 lg:grid-cols-12 gap-5">
        <!-- Kolom Kiri (7 Kolom): Metrik Saldo & Target -->
        <div class="lg:col-span-7 border border-zinc-200 rounded-lg bg-white divide-y divide-zinc-200">
            <div class="px-5 py-3.5 bg-zinc-50/70 flex items-center justify-between">
                <h2 class="text-xs font-semibold uppercase tracking-wider text-zinc-600">Saldo & Capaian Kerja</h2>
                <span class="text-[11px] font-mono text-zinc-400">Tahun {{ now()->year }}</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-zinc-200">
                <!-- Sisa Kuota Cuti -->
                <div class="p-5 flex flex-col justify-between">
                    <span class="text-xs text-zinc-500">Sisa Kuota Cuti</span>
                    <div class="my-3">
                        <span class="text-2xl font-semibold text-zinc-900 tabular-nums">{{ $sisaKuotaCuti }}</span>
                        <span class="text-xs text-zinc-400 tabular-nums">/ {{ $kuotaTahunan }} hari</span>
                    </div>
                    <div class="w-full bg-zinc-100 h-1 rounded-full overflow-hidden">
                        <div class="bg-emerald-700 h-full" style="width: {{ ($sisaKuotaCuti / max(1, $kuotaTahunan)) * 100 }}%"></div>
                    </div>
                </div>

                <!-- Batas Wellness Budget -->
                <div class="p-5 flex flex-col justify-between">
                    <span class="text-xs text-zinc-500">Wellness Budget</span>
                    <div class="my-3">
                        <div class="text-base font-semibold text-zinc-900 tabular-nums truncate">
                            Rp {{ number_format($sisaWellness, 0, ',', '.') }}
                        </div>
                        <div class="text-[11px] text-zinc-400 tabular-nums mt-0.5">
                            Batas Rp {{ number_format($batasWellness, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="w-full bg-zinc-100 h-1 rounded-full overflow-hidden">
                        <div class="bg-emerald-700 h-full" style="width: {{ ($sisaWellness / max(1, $batasWellness)) * 100 }}%"></div>
                    </div>
                </div>

                <!-- Capaian OKR Kuartal -->
                <div class="p-5 flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-zinc-500">Capaian OKR</span>
                        <span class="text-[10px] font-mono text-zinc-500">Q4</span>
                    </div>
                    <div class="my-3">
                        <span class="text-2xl font-semibold text-zinc-900 tabular-nums">{{ $persenOkr }}%</span>
                        <span class="text-xs text-zinc-400">selesai</span>
                    </div>
                    <div class="w-full bg-zinc-100 h-1 rounded-full overflow-hidden">
                        <div class="bg-emerald-700 h-full" style="width: {{ $persenOkr }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Baris Tambahan: Akses Modul Pendukung -->
            <div class="px-5 py-3.5 bg-zinc-50/40 flex flex-wrap items-center justify-between gap-3 text-xs">
                <div class="flex flex-wrap items-center gap-x-5 gap-y-2">
                    <a href="{{ route('pegawai.tugas.index') }}" class="font-medium text-zinc-700 hover:text-emerald-700 underline-offset-4 hover:underline">
                        Daftar Tugas ({{ $jumlahTugasAktif }} aktif)
                    </a>
                    <a href="{{ route('pegawai.klaim.index') }}" class="font-medium text-zinc-700 hover:text-emerald-700 underline-offset-4 hover:underline">
                        Klaim Struk
                    </a>
                    <a href="{{ route('pegawai.kudos.index') }}" class="font-medium text-zinc-700 hover:text-emerald-700 underline-offset-4 hover:underline">
                        Kirim Kudos
                    </a>
                </div>
                <a href="{{ route('pegawai.profil.index') }}" class="text-zinc-500 hover:text-zinc-900 font-medium">
                    Pengaturan Akun &rarr;
                </a>
            </div>
        </div>

        <!-- Kolom Kanan (5 Kolom): Info Tim Hari Ini (Who's Out) -->
        <div class="lg:col-span-5 border border-zinc-200 rounded-lg bg-white flex flex-col">
            <div class="px-5 py-3.5 border-b border-zinc-200 bg-zinc-50/70 flex items-center justify-between">
                <h2 class="text-xs font-semibold uppercase tracking-wider text-zinc-600">Tim Hari Ini (Who’s Out)</h2>
                <span class="text-xs font-mono text-zinc-500 tabular-nums">
                    {{ $timCutiHariIni->count() }} tidak hadir
                </span>
            </div>

            <div class="p-5 flex-1 flex flex-col justify-center">
                @if($timCutiHariIni->isEmpty())
                    <div class="py-4">
                        <p class="text-sm font-medium text-zinc-800">Seluruh anggota tim hadir.</p>
                        <p class="text-xs text-zinc-500 mt-1">Tidak ada rekan kerja yang sedang cuti atau izin pada tanggal ini.</p>
                    </div>
                @else
                    <ul class="divide-y divide-zinc-100 -my-2 max-h-48 overflow-y-auto">
                        @foreach($timCutiHariIni as $item)
                            <li class="py-2.5 flex items-baseline justify-between gap-3 text-xs">
                                <span class="font-medium text-zinc-900 truncate">
                                    {{ $item->pegawai->nama_lengkap }}
                                </span>
                                <span class="text-zinc-500 shrink-0">
                                    {{ $item->keterangan }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection