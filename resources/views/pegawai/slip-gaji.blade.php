@extends('layouts.app')
@section('title', 'Slip Gaji — ' . $pegawai->nama_lengkap)

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="pb-5 border-b border-zinc-200 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <a href="{{ route('pegawai.dashboard') }}" class="inline-block text-xs font-medium text-zinc-500 hover:text-zinc-900 mb-2">
                &larr; Kembali ke Ringkasan
            </a>
            <h1 class="text-xl font-semibold tracking-tight text-zinc-900">Rincian Slip Gaji</h1>
            <p class="text-xs text-zinc-500 mt-0.5">{{ $pegawai->kode_pegawai }} · {{ $pegawai->jabatan }}</p>
        </div>
    </div>

    @if($slipGaji->isNotEmpty())
        @php $terbaru = $slipGaji->first(); @endphp
        <!-- Sorotan Periode Terbaru -->
        <div class="mt-6 border border-zinc-200 rounded-lg bg-white p-5 sm:p-6">
            <div class="flex flex-wrap items-baseline justify-between gap-2 pb-4 border-b border-zinc-100">
                <div>
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-zinc-400">Periode Terbaru</span>
                    <h2 class="text-base font-semibold font-mono text-zinc-900 mt-0.5">{{ $terbaru->periode_bulan }}</h2>
                </div>
                <div class="text-left sm:text-right">
                    <span class="text-[11px] text-zinc-500 block">Total Diterima (Take-Home Pay)</span>
                    <span class="text-2xl font-semibold text-emerald-700 tabular-nums">
                        Rp {{ number_format($terbaru->total_gaji, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <div class="pt-4 grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs tabular-nums">
                <div>
                    <span class="text-zinc-500 block">Gaji Pokok</span>
                    <span class="font-semibold text-zinc-900 mt-0.5 block">Rp {{ number_format($terbaru->gaji_pokok, 0, ',', '.') }}</span>
                </div>
                <div>
                    <span class="text-zinc-500 block">Tunjangan</span>
                    <span class="font-semibold text-zinc-900 mt-0.5 block">+ Rp {{ number_format($terbaru->tunjangan, 0, ',', '.') }}</span>
                </div>
                <div>
                    <span class="text-zinc-500 block">Potongan</span>
                    <span class="font-semibold text-zinc-900 mt-0.5 block">- Rp {{ number_format($terbaru->potongan, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    @endif

    <!-- Tabel Arsip Seluruh Slip Gaji -->
    <div class="mt-6 border border-zinc-200 rounded-lg bg-white overflow-hidden">
        <div class="px-5 py-3.5 border-b border-zinc-200 bg-zinc-50/70 flex items-center justify-between">
            <h2 class="text-xs font-semibold uppercase tracking-wider text-zinc-600">Riwayat Penerbitan Slip</h2>
            <span class="text-xs font-mono text-zinc-400 tabular-nums">{{ $slipGaji->count() }} periode</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="border-b border-zinc-200 bg-zinc-50/40 text-zinc-500 uppercase">
                    <tr>
                        <th class="px-5 py-3 font-semibold">Periode</th>
                        <th class="px-5 py-3 font-semibold text-right">Gaji Pokok</th>
                        <th class="px-5 py-3 font-semibold text-right">Tunjangan</th>
                        <th class="px-5 py-3 font-semibold text-right">Potongan</th>
                        <th class="px-5 py-3 font-semibold text-right">Total Bersih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 tabular-nums">
                    @forelse($slipGaji as $slip)
                        <tr class="hover:bg-zinc-50/70">
                            <td class="px-5 py-3 font-mono font-semibold text-zinc-900">{{ $slip->periode_bulan }}</td>
                            <td class="px-5 py-3 text-right text-zinc-700">Rp {{ number_format($slip->gaji_pokok, 0, ',', '.') }}</td>
                            <td class="px-5 py-3 text-right text-zinc-700">Rp {{ number_format($slip->tunjangan, 0, ',', '.') }}</td>
                            <td class="px-5 py-3 text-right text-zinc-500">Rp {{ number_format($slip->potongan, 0, ',', '.') }}</td>
                            <td class="px-5 py-3 text-right font-semibold text-emerald-700">
                                Rp {{ number_format($slip->total_gaji, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-zinc-500">
                                Belum ada slip gaji yang diterbitkan oleh admin.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection