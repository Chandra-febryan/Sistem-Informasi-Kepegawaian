@extends('layouts.app')
@section('title', 'Ajuan Cuti — ' . $pegawai->nama_lengkap)

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="pb-5 border-b border-zinc-200">
        <a href="{{ route('pegawai.dashboard') }}" class="inline-block text-xs font-medium text-zinc-500 hover:text-zinc-900 mb-2">
            &larr; Kembali ke Ringkasan
        </a>
        <h1 class="text-xl font-semibold tracking-tight text-zinc-900">Permohonan Cuti</h1>
        <p class="text-xs text-zinc-500 mt-0.5">Ajukan tanggal cuti dan pantau status persetujuan admin</p>
    </div>

    <!-- Split Layout: Form Pengajuan (Kiri) & Riwayat (Kanan) -->
    <div class="mt-6 grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Form Pengajuan -->
        <div class="lg:col-span-5 border border-zinc-200 rounded-lg bg-white p-5 sm:p-6">
            <h2 class="text-sm font-semibold text-zinc-900 mb-4">Form Pengajuan Baru</h2>

            <form action="{{ route('pegawai.cuti') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-zinc-700 mb-1">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required
                               class="w-full border border-zinc-300 rounded-md px-3 py-2 text-xs text-zinc-900 focus:outline-none focus:border-emerald-700">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-zinc-700 mb-1">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required
                               class="w-full border border-zinc-300 rounded-md px-3 py-2 text-xs text-zinc-900 focus:outline-none focus:border-emerald-700">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-zinc-700 mb-1">Keterangan / Alasan</label>
                    <textarea name="keterangan" rows="3" placeholder="Tuliskan alasan permohonan cuti..." required
                              class="w-full border border-zinc-300 rounded-md px-3 py-2 text-xs text-zinc-900 focus:outline-none focus:border-emerald-700">{{ old('keterangan') }}</textarea>
                </div>

                <button type="submit"
                        class="w-full bg-emerald-700 hover:bg-emerald-800 text-white py-2.5 px-4 rounded-md text-xs font-semibold transition-colors">
                    Kirim Permohonan Cuti
                </button>
            </form>
        </div>

        <!-- Tabel / Daftar Riwayat Pengajuan -->
        <div class="lg:col-span-7 border border-zinc-200 rounded-lg bg-white overflow-hidden">
            <div class="px-5 py-3.5 border-b border-zinc-200 bg-zinc-50/70 flex items-center justify-between">
                <h2 class="text-xs font-semibold uppercase tracking-wider text-zinc-600">Daftar Permohonan Anda</h2>
                <span class="text-xs font-mono text-zinc-400 tabular-nums">{{ $daftarCuti->count() }} riwayat</span>
            </div>

            <div class="divide-y divide-zinc-200">
                @forelse($daftarCuti as $cuti)
                    <div class="p-4 sm:px-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-xs font-mono font-semibold text-zinc-900 tabular-nums">
                                {{ $cuti->tanggal_mulai }} &rarr; {{ $cuti->tanggal_selesai }}
                            </p>
                            <p class="text-xs text-zinc-600 mt-1">{{ $cuti->keterangan }}</p>
                        </div>

                        <div class="shrink-0">
                            @if($cuti->status === 'disetujui')
                                <span class="inline-block px-2.5 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-semibold">
                                    Disetujui
                                </span>
                            @elseif($cuti->status === 'ditolak')
                                <span class="inline-block px-2.5 py-0.5 rounded bg-zinc-900 text-white text-[11px] font-semibold">
                                    Ditolak
                                </span>
                            @else
                                <span class="inline-block px-2.5 py-0.5 rounded bg-zinc-100 text-zinc-700 border border-zinc-200 text-[11px] font-semibold">
                                    Menunggu
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-10 text-center text-xs text-zinc-500">
                        Belum ada permohonan cuti yang diajukan.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection