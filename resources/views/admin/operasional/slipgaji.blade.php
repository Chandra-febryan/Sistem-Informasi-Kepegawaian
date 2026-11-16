@extends('layouts.app')
@section('title', 'Penerbitan Slip Gaji — Admin')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="pb-5 border-b border-zinc-200">
        <a href="{{ route('admin.dashboard') }}" class="inline-block text-xs font-medium text-zinc-500 hover:text-zinc-900 mb-2">
            &larr; Kembali ke Dashboard
        </a>
        <h1 class="text-xl font-semibold tracking-tight text-zinc-900">Penerbitan Slip Gaji</h1>
        <p class="text-xs text-zinc-500 mt-0.5">Tetapkan gaji pokok, tunjangan, dan potongan bulanan pegawai</p>
    </div>

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Form Penerbitan (4 Kolom) -->
        <div class="lg:col-span-4 border border-zinc-200 rounded-lg bg-white p-5">
            <h2 class="text-xs font-semibold uppercase tracking-wider text-zinc-600 mb-4">Terbitkan Slip Baru</h2>

            <form action="{{ route('admin.slip-gaji.store') }}" method="POST" class="space-y-3.5">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-zinc-700 mb-1">Pegawai Penerima</label>
                    <select name="pegawai_id" required
                            class="w-full border border-zinc-300 rounded-md px-3 py-2 text-xs bg-white text-zinc-900 focus:outline-none focus:border-emerald-700">
                        @foreach($pegawais as $p)
                            <option value="{{ $p->id }}">{{ $p->kode_pegawai }} — {{ $p->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-zinc-700 mb-1">Periode Bulan (YYYY-MM)</label>
                    <input type="text" name="periode_bulan" value="{{ now()->format('Y-m') }}" required
                           class="w-full border border-zinc-300 rounded-md px-3 py-2 text-xs font-mono text-zinc-900 focus:outline-none focus:border-emerald-700">
                </div>

                <div>
                    <label class="block text-xs font-medium text-zinc-700 mb-1">Gaji Pokok (Rp)</label>
                    <input type="number" name="gaji_pokok" min="0" required
                           class="w-full border border-zinc-300 rounded-md px-3 py-2 text-xs text-zinc-900 tabular-nums focus:outline-none focus:border-emerald-700">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-zinc-700 mb-1">Tunjangan (Rp)</label>
                        <input type="number" name="tunjangan" value="0" min="0"
                               class="w-full border border-zinc-300 rounded-md px-3 py-2 text-xs text-zinc-900 tabular-nums focus:outline-none focus:border-emerald-700">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-zinc-700 mb-1">Potongan (Rp)</label>
                        <input type="number" name="potongan" value="0" min="0"
                               class="w-full border border-zinc-300 rounded-md px-3 py-2 text-xs text-zinc-900 tabular-nums focus:outline-none focus:border-emerald-700">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full bg-emerald-700 hover:bg-emerald-800 text-white py-2.5 px-4 rounded-md text-xs font-semibold transition-colors">
                        Simpan & Terbitkan Slip
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Daftar Slip Gaji (8 Kolom) -->
        <div class="lg:col-span-8 border border-zinc-200 rounded-lg bg-white overflow-hidden">
            <div class="px-5 py-3.5 border-b border-zinc-200 bg-zinc-50/70 flex items-center justify-between">
                <h2 class="text-xs font-semibold uppercase tracking-wider text-zinc-600">Arsip Slip Terbit</h2>
                <span class="text-xs font-mono text-zinc-400 tabular-nums">{{ $slipGajis->count() }} slip</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-zinc-200 bg-zinc-50/40 text-zinc-500 uppercase">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Pegawai</th>
                            <th class="px-4 py-3 font-semibold">Periode</th>
                            <th class="px-4 py-3 font-semibold">Komponen</th>
                            <th class="px-4 py-3 font-semibold text-right">Total Bersih</th>
                            <th class="px-4 py-3 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 tabular-nums">
                        @forelse($slipGajis as $s)
                            <tr class="hover:bg-zinc-50/70">
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-zinc-900">{{ $s->pegawai->nama_lengkap }}</div>
                                    <div class="font-mono text-[11px] text-zinc-500">{{ $s->pegawai->kode_pegawai }}</div>
                                </td>
                                <td class="px-4 py-3 font-mono text-zinc-800">{{ $s->periode_bulan }}</td>
                                <td class="px-4 py-3 text-[11px] text-zinc-500">
                                    <div>Pokok: Rp {{ number_format($s->gaji_pokok, 0, ',', '.') }}</div>
                                    <div>+Rp {{ number_format($s->tunjangan, 0, ',', '.') }} / -Rp {{ number_format($s->potongan, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-4 py-3 text-right font-semibold text-emerald-700 whitespace-nowrap">
                                    Rp {{ number_format($s->total_gaji, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <form action="{{ route('admin.slip-gaji.destroy', $s) }}" method="POST"
                                          onsubmit="return confirm('Hapus slip gaji periode {{ $s->periode_bulan }} untuk {{ $s->pegawai->nama_lengkap }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-zinc-500 hover:text-red-700 font-medium">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center text-zinc-500">
                                    Belum ada slip gaji yang diterbitkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection