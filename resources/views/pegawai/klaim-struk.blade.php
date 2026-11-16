@extends('layouts.app')
@section('title', 'Klaim Struk Pembayaran — ' . $pegawai->nama_lengkap)

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="pb-5 border-b border-zinc-200">
        <a href="{{ route('pegawai.dashboard') }}" class="inline-block text-xs font-medium text-zinc-500 hover:text-zinc-900 mb-2">
            &larr; Kembali ke Ringkasan
        </a>
        <h1 class="text-xl font-semibold tracking-tight text-zinc-900">Pengajuan Klaim Struk Pembayaran</h1>
        <p class="text-xs text-zinc-500 mt-0.5">Catat rincian nota atau bukti pembayaran operasional untuk proses penggantian dana</p>
    </div>

    <!-- Split Layout: Form Pengajuan (Kiri) & Riwayat (Kanan) -->
    <div class="mt-6 grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Form Pengajuan Klaim -->
        <div class="lg:col-span-5 border border-zinc-200 rounded-lg bg-white p-5 sm:p-6">
            <h2 class="text-xs font-semibold uppercase tracking-wider text-zinc-600 mb-4">Form Klaim Baru</h2>

            <form action="{{ route('pegawai.klaim.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="kategori" value="Pembayaran">

                <div>
                    <label class="block text-xs font-medium text-zinc-700 mb-1">Nominal Struk (Rp)</label>
                    <input type="number" name="nominal" value="{{ old('nominal') }}" min="1000" placeholder="Contoh: 150000" required
                           class="w-full border border-zinc-300 rounded-md px-3 py-2 text-xs text-zinc-900 tabular-nums focus:outline-none focus:border-emerald-700">
                </div>

                <div>
                    <label class="block text-xs font-medium text-zinc-700 mb-1">Rincian / Nomor Nota Pembayaran</label>
                    <textarea name="keterangan" rows="3" placeholder="Contoh: Pembelian perlengkapan kerja / Biaya transportasi dinas (No. Nota #INV-012)" required
                              class="w-full border border-zinc-300 rounded-md px-3 py-2 text-xs text-zinc-900 focus:outline-none focus:border-emerald-700">{{ old('keterangan') }}</textarea>
                </div>

                <button type="submit"
                        class="w-full bg-emerald-700 hover:bg-emerald-800 text-white py-2.5 px-4 rounded-md text-xs font-semibold transition-colors">
                    Kirim Klaim Pembayaran
                </button>
            </form>
        </div>

        <!-- Daftar Riwayat Klaim -->
        <div class="lg:col-span-7 border border-zinc-200 rounded-lg bg-white overflow-hidden">
            <div class="px-5 py-3.5 border-b border-zinc-200 bg-zinc-50/70 flex items-center justify-between">
                <h2 class="text-xs font-semibold uppercase tracking-wider text-zinc-600">Riwayat Klaim Anda</h2>
                <span class="text-xs font-mono text-zinc-400 tabular-nums">{{ $daftarKlaim->count() }} pengajuan</span>
            </div>

            <div class="divide-y divide-zinc-200 tabular-nums">
                @forelse($daftarKlaim as $klaim)
                    <div class="p-4 sm:px-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-zinc-900">
                                Rp {{ number_format($klaim->nominal, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-zinc-600 mt-0.5">{{ $klaim->keterangan }}</p>
                            <p class="text-[11px] font-mono text-zinc-400 mt-1">{{ $klaim->created_at->format('d M Y, H:i') }} WIB</p>
                        </div>

                        <div class="shrink-0">
                            @if($klaim->status === 'disetujui')
                                <span class="inline-block px-2.5 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-semibold">
                                    Disetujui
                                </span>
                            @elseif($klaim->status === 'ditolak')
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
                        Belum ada pengajuan klaim struk pembayaran.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection