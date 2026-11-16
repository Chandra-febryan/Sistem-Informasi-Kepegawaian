@extends('layouts.app')
@section('title', 'Monitoring Absensi — Admin')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header & Filter Tanggal -->
    <div class="pb-5 border-b border-zinc-200 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="inline-block text-xs font-medium text-zinc-500 hover:text-zinc-900 mb-2">
                &larr; Kembali ke Dashboard
            </a>
            <h1 class="text-xl font-semibold tracking-tight text-zinc-900">Monitoring Presensi Pegawai</h1>
            <p class="text-xs text-zinc-500 mt-0.5">Rekap jam masuk, jam pulang, dan lokasi kerja harian</p>
        </div>

        <form action="{{ route('admin.absensi') }}" method="GET" class="flex items-center gap-2">
            <input type="date" name="tanggal" value="{{ $tanggal }}"
                   class="border border-zinc-300 bg-white rounded-md px-3 py-1.5 text-xs text-zinc-900 focus:outline-none focus:border-emerald-700">
            <button type="submit"
                    class="bg-zinc-900 hover:bg-zinc-800 text-white px-3.5 py-1.5 rounded-md text-xs font-semibold transition-colors">
                Filter Tanggal
            </button>
        </form>
    </div>

    <!-- Tabel Presensi -->
    <div class="mt-6 border border-zinc-200 rounded-lg bg-white overflow-hidden">
        <div class="px-5 py-3.5 border-b border-zinc-200 bg-zinc-50/70 flex items-center justify-between">
            <h2 class="text-xs font-semibold uppercase tracking-wider text-zinc-600">
                Data Tanggal {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d M Y') }}
            </h2>
            <span class="text-xs font-mono text-zinc-500 tabular-nums">{{ $absensis->count() }} hadir</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="border-b border-zinc-200 bg-zinc-50/40 text-zinc-500 uppercase">
                    <tr>
                        <th class="px-5 py-3 font-semibold">Kode</th>
                        <th class="px-5 py-3 font-semibold">Nama Pegawai</th>
                        <th class="px-5 py-3 font-semibold">Lokasi</th>
                        <th class="px-5 py-3 font-semibold">Jam Masuk</th>
                        <th class="px-5 py-3 font-semibold">Jam Keluar</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 tabular-nums">
                    @forelse($absensis as $absen)
                        <tr class="hover:bg-zinc-50/70">
                            <td class="px-5 py-3 font-mono text-zinc-600">{{ $absen->pegawai->kode_pegawai }}</td>
                            <td class="px-5 py-3 font-semibold text-zinc-900">{{ $absen->pegawai->nama_lengkap }}</td>
                            <td class="px-5 py-3 font-mono text-zinc-700">{{ $absen->lokasi ?? 'WFO' }}</td>
                            <td class="px-5 py-3 font-mono text-zinc-800">{{ $absen->jam_masuk }}</td>
                            <td class="px-5 py-3 font-mono text-zinc-800">{{ $absen->jam_keluar ?? '—' }}</td>
                            <td class="px-5 py-3">
                                @if($absen->status === 'hadir')
                                    <span class="inline-block px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200 font-medium">
                                        Tepat Waktu
                                    </span>
                                @else
                                    <span class="inline-block px-2 py-0.5 rounded bg-zinc-100 text-zinc-800 border border-zinc-300 font-medium">
                                        Terlambat
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-zinc-500">
                                Tidak ada catatan presensi pada tanggal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection