@extends('layouts.app')
@section('title', 'Absensi — ' . $pegawai->nama_lengkap)

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Navigasi Kembali & Header -->
    <div class="pb-5 border-b border-zinc-200 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <a href="{{ route('pegawai.dashboard') }}" class="inline-block text-xs font-medium text-zinc-500 hover:text-zinc-900 mb-2">
                &larr; Kembali ke Ringkasan
            </a>
            <h1 class="text-xl font-semibold tracking-tight text-zinc-900">Pencatatan Presensi</h1>
            <p class="text-xs text-zinc-500 mt-0.5">Batas jam masuk normal pukul 09:15 WIB</p>
        </div>

        <span class="text-xs font-mono text-zinc-600 bg-white px-3 py-1.5 rounded-md border border-zinc-200 tabular-nums self-start sm:self-auto">
            {{ now()->translatedFormat('d M Y') }}
        </span>
    </div>

    <!-- Form Pencatatan Hadir Hari Ini -->
    <div class="mt-6 border border-zinc-200 rounded-lg bg-white p-5 sm:p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-sm font-semibold text-zinc-900">Status Presensi Hari Ini</h2>
                @if(!$absensiHariIni)
                    <p class="text-xs text-zinc-500 mt-1">Pilih lokasi kerja dan catat waktu kedatangan Anda.</p>
                @elseif(!$absensiHariIni->jam_keluar)
                    <p class="text-xs text-zinc-600 mt-1 tabular-nums">
                        Tercatat masuk pukul <strong class="font-mono text-zinc-900">{{ $absensiHariIni->jam_masuk }}</strong>
                        ({{ $absensiHariIni->lokasi ?? 'WFO' }} · {{ ucfirst($absensiHariIni->status) }})
                    </p>
                @else
                    <p class="text-xs text-zinc-600 mt-1 tabular-nums">
                        Presensi selesai: <strong class="font-mono text-zinc-900">{{ $absensiHariIni->jam_masuk }}</strong> s/d
                        <strong class="font-mono text-zinc-900">{{ $absensiHariIni->jam_keluar }}</strong>
                        ({{ $absensiHariIni->lokasi ?? 'WFO' }})
                    </p>
                @endif
            </div>

            <div class="shrink-0">
                @if(!$absensiHariIni)
                    <form action="{{ route('pegawai.checkin') }}" method="POST" class="flex flex-wrap items-center gap-3">
                        @csrf
                        <div class="inline-flex border border-zinc-200 rounded-md p-0.5 bg-zinc-50 text-xs font-medium">
                            <label class="cursor-pointer">
                                <input type="radio" name="lokasi" value="WFO" checked class="peer sr-only">
                                <span class="block px-3 py-1.5 rounded peer-checked:bg-white peer-checked:text-zinc-900 peer-checked:shadow-xs text-zinc-500 transition-colors">
                                    WFO
                                </span>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="lokasi" value="WFH" class="peer sr-only">
                                <span class="block px-3 py-1.5 rounded peer-checked:bg-white peer-checked:text-zinc-900 peer-checked:shadow-xs text-zinc-500 transition-colors">
                                    WFH
                                </span>
                            </label>
                        </div>

                        <button type="submit"
                                class="bg-emerald-700 hover:bg-emerald-800 text-white px-4 py-2 rounded-md text-xs font-semibold transition-colors">
                            Catat Jam Masuk
                        </button>
                    </form>
                @elseif(!$absensiHariIni->jam_keluar)
                    <form action="{{ route('pegawai.checkout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="bg-zinc-900 hover:bg-zinc-800 text-white px-4 py-2 rounded-md text-xs font-semibold transition-colors">
                            Catat Jam Pulang
                        </button>
                    </form>
                @else
                    <span class="inline-block px-3 py-1.5 rounded-md bg-zinc-100 border border-zinc-200 text-zinc-700 text-xs font-medium">
                        Tercatat Lengkap
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Tabel Riwayat Presensi -->
    <div class="mt-6 border border-zinc-200 rounded-lg bg-white overflow-hidden">
        <div class="px-5 py-3.5 border-b border-zinc-200 bg-zinc-50/70 flex items-center justify-between">
            <h2 class="text-xs font-semibold uppercase tracking-wider text-zinc-600">Riwayat Presensi Terbaru</h2>
            <span class="text-xs font-mono text-zinc-400 tabular-nums">{{ $riwayatAbsensi->count() }} entri</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="border-b border-zinc-200 bg-zinc-50/40 text-zinc-500 uppercase">
                    <tr>
                        <th class="px-5 py-3 font-semibold">Tanggal</th>
                        <th class="px-5 py-3 font-semibold">Lokasi</th>
                        <th class="px-5 py-3 font-semibold">Jam Masuk</th>
                        <th class="px-5 py-3 font-semibold">Jam Keluar</th>
                        <th class="px-5 py-3 font-semibold">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 tabular-nums">
                    @forelse($riwayatAbsensi as $absen)
                        <tr class="hover:bg-zinc-50/70">
                            <td class="px-5 py-3 font-medium text-zinc-900">{{ $absen->tanggal }}</td>
                            <td class="px-5 py-3 font-mono text-zinc-600">{{ $absen->lokasi ?? 'WFO' }}</td>
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
                            <td colspan="5" class="px-5 py-8 text-center text-zinc-500">Belum ada data presensi tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection