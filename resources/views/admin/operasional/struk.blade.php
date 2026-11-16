@extends('layouts.app')
@section('title', 'Verifikasi Klaim Struk — Admin')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="pb-5 border-b border-zinc-200 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="inline-block text-xs font-medium text-zinc-500 hover:text-zinc-900 mb-2">
                &larr; Kembali ke Dashboard
            </a>
            <h1 class="text-xl font-semibold tracking-tight text-zinc-900">Verifikasi Klaim Struk Pembayaran</h1>
            <p class="text-xs text-zinc-500 mt-0.5">Tinjau dan setujui pengajuan penggantian biaya pembayaran pegawai</p>
        </div>

        <span class="text-xs font-mono text-zinc-600 bg-white px-3 py-1.5 rounded-md border border-zinc-200 tabular-nums self-start sm:self-auto">
            {{ $klaims->where('status', 'pending')->count() }} Menunggu Verifikasi
        </span>
    </div>

    <!-- Tabel Daftar Pengajuan Klaim -->
    <div class="mt-6 border border-zinc-200 rounded-lg bg-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="border-b border-zinc-200 bg-zinc-50/70 text-zinc-500 uppercase">
                    <tr>
                        <th class="px-5 py-3 font-semibold">Pegawai</th>
                        <th class="px-5 py-3 font-semibold">Tanggal Ajuan</th>
                        <th class="px-5 py-3 font-semibold">Rincian Struk Pembayaran</th>
                        <th class="px-5 py-3 font-semibold text-right">Nominal</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                        <th class="px-5 py-3 font-semibold text-right">Keputusan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 tabular-nums">
                    @forelse($klaims as $klaim)
                        <tr class="hover:bg-zinc-50/70">
                            <td class="px-5 py-3.5">
                                <div class="font-semibold text-zinc-900">{{ $klaim->pegawai->nama_lengkap }}</div>
                                <div class="font-mono text-[11px] text-zinc-500">{{ $klaim->pegawai->kode_pegawai }} · {{ $klaim->pegawai->jabatan }}</div>
                            </td>
                            <td class="px-5 py-3.5 font-mono text-zinc-600 whitespace-nowrap">
                                {{ $klaim->created_at->format('d M Y') }}
                            </td>
                            <td class="px-5 py-3.5 text-zinc-700 max-w-xs">
                                {{ $klaim->keterangan }}
                            </td>
                            <td class="px-5 py-3.5 text-right font-semibold text-zinc-900 whitespace-nowrap">
                                Rp {{ number_format($klaim->nominal, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-3.5">
                                @if($klaim->status === 'disetujui')
                                    <span class="inline-block px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200 font-semibold">
                                        Disetujui
                                    </span>
                                @elseif($klaim->status === 'ditolak')
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
                                @if($klaim->status === 'pending')
                                    <div class="inline-flex items-center gap-1.5">
                                        <form action="{{ route('admin.struk.update', $klaim) }}" method="POST"
                                              onsubmit="return confirm('Setujui klaim pembayaran senilai Rp {{ number_format($klaim->nominal, 0, ',', '.') }} untuk {{ $klaim->pegawai->nama_lengkap }}?')">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="disetujui">
                                            <button type="submit"
                                                    class="bg-emerald-700 hover:bg-emerald-800 text-white px-2.5 py-1 rounded text-[11px] font-semibold transition-colors">
                                                Setujui
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.struk.update', $klaim) }}" method="POST"
                                              onsubmit="return confirm('Tolak klaim pembayaran dari {{ $klaim->pegawai->nama_lengkap }}?')">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="ditolak">
                                            <button type="submit"
                                                    class="border border-zinc-300 bg-white hover:bg-zinc-900 hover:text-white hover:border-zinc-900 text-zinc-700 px-2.5 py-1 rounded text-[11px] font-semibold transition-colors">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-[11px] font-mono text-zinc-400">Terverifikasi</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-zinc-500">
                                Belum ada pengajuan klaim struk pembayaran dari pegawai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection