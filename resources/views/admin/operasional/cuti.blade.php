@extends('layouts.app')
@section('title', 'Persetujuan Cuti — Admin')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="pb-5 border-b border-zinc-200 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="inline-block text-xs font-medium text-zinc-500 hover:text-zinc-900 mb-2">
                &larr; Kembali ke Dashboard
            </a>
            <h1 class="text-xl font-semibold tracking-tight text-zinc-900">Persetujuan Cuti Pegawai</h1>
            <p class="text-xs text-zinc-500 mt-0.5">Tinjau dan tetapkan keputusan permohonan cuti</p>
        </div>

        <span class="text-xs font-mono text-zinc-600 bg-white px-3 py-1.5 rounded-md border border-zinc-200 tabular-nums self-start sm:self-auto">
            {{ $cutis->where('status', 'pending')->count() }} Menunggu Keputusan
        </span>
    </div>

    <!-- Tabel Pengajuan Cuti -->
    <div class="mt-6 border border-zinc-200 rounded-lg bg-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="border-b border-zinc-200 bg-zinc-50/70 text-zinc-500 uppercase">
                    <tr>
                        <th class="px-5 py-3 font-semibold">Pegawai</th>
                        <th class="px-5 py-3 font-semibold">Periode Cuti</th>
                        <th class="px-5 py-3 font-semibold">Keterangan</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                        <th class="px-5 py-3 font-semibold text-right">Keputusan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 tabular-nums">
                    @forelse($cutis as $cuti)
                        <tr class="hover:bg-zinc-50/70">
                            <td class="px-5 py-3.5">
                                <div class="font-semibold text-zinc-900">{{ $cuti->pegawai->nama_lengkap }}</div>
                                <div class="font-mono text-[11px] text-zinc-500">{{ $cuti->pegawai->kode_pegawai }} · {{ $cuti->pegawai->jabatan }}</div>
                            </td>
                            <td class="px-5 py-3.5 font-mono text-zinc-800 whitespace-nowrap">
                                {{ $cuti->tanggal_mulai }} &rarr; {{ $cuti->tanggal_selesai }}
                            </td>
                            <td class="px-5 py-3.5 text-zinc-600 max-w-xs">
                                {{ $cuti->keterangan }}
                            </td>
                            <td class="px-5 py-3.5">
                                @if($cuti->status === 'disetujui')
                                    <span class="inline-block px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200 font-semibold">
                                        Disetujui
                                    </span>
                                @elseif($cuti->status === 'ditolak')
                                    <span class="inline-block px-2 py-0.5 rounded bg-zinc-900 text-white font-semibold">
                                        Ditolak
                                    </span>
                                @else
                                    <span class="inline-block px-2 py-0.5 rounded bg-zinc-100 text-zinc-700 border border-zinc-200 font-semibold">
                                        Menunggu
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                @if($cuti->status === 'pending')
                                    <div class="inline-flex items-center gap-1.5">
                                        <form action="{{ route('admin.cuti.update', $cuti) }}" method="POST"
                                              onsubmit="return confirm('Setujui pengajuan cuti untuk {{ $cuti->pegawai->nama_lengkap }}?')">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="disetujui">
                                            <button type="submit"
                                                    class="bg-emerald-700 hover:bg-emerald-800 text-white px-2.5 py-1 rounded text-[11px] font-semibold transition-colors">
                                                Setujui
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.cuti.update', $cuti) }}" method="POST"
                                              onsubmit="return confirm('Tolak pengajuan cuti untuk {{ $cuti->pegawai->nama_lengkap }}?')">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="ditolak">
                                            <button type="submit"
                                                    class="border border-zinc-300 bg-white hover:bg-zinc-900 hover:text-white hover:border-zinc-900 text-zinc-700 px-2.5 py-1 rounded text-[11px] font-semibold transition-colors">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-[11px] font-mono text-zinc-400">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-zinc-500">
                                Belum ada pengajuan cuti dari pegawai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection