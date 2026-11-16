@extends('layouts.app')
@section('title', 'Daftar Tugas — ' . $pegawai->nama_lengkap)

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="pb-5 border-b border-zinc-200 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <a href="{{ route('pegawai.dashboard') }}" class="inline-block text-xs font-medium text-zinc-500 hover:text-zinc-900 mb-2">
                &larr; Kembali ke Ringkasan
            </a>
            <h1 class="text-xl font-semibold tracking-tight text-zinc-900">Daftar Tugas & Target Bulanan</h1>
            <p class="text-xs text-zinc-500 mt-0.5">Kelola target kerja dan perbarui status penyelesaian untuk kalkulasi OKR</p>
        </div>
    </div>

    <!-- Form Tambah Tugas Baru (Dengan Deskripsi) -->
    <div class="mt-6 border border-zinc-200 rounded-lg bg-white p-5">
        <h2 class="text-xs font-semibold uppercase tracking-wider text-zinc-600 mb-3">Tambah Tugas Baru</h2>
        <form action="{{ route('pegawai.tugas.store') }}" method="POST" class="space-y-3">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                <div class="sm:col-span-8">
                    <label class="block text-[11px] font-medium text-zinc-600 mb-1">Judul Tugas</label>
                    <input type="text" name="judul_tugas" value="{{ old('judul_tugas') }}" placeholder="Contoh: Integrasi API Payment Gateway" required
                           class="w-full border border-zinc-300 rounded-md px-3 py-2 text-xs text-zinc-900 focus:outline-none focus:border-emerald-700">
                </div>
                <div class="sm:col-span-4">
                    <label class="block text-[11px] font-medium text-zinc-600 mb-1">Bulan Target</label>
                    <input type="date" name="bulan_target" value="{{ old('bulan_target',now()->endOfMonth()->format('d-m-Y') ) }}" required
                           class="w-full border border-zinc-300 rounded-md px-3 py-2 text-xs text-zinc-900 focus:outline-none focus:border-emerald-700">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-medium text-zinc-600 mb-1">Deskripsi Detail (Opsional)</label>
                <textarea name="deskripsi" rows="2" placeholder="Catatan teknis, kriteria selesai, atau tautan referensi..."
                          class="w-full border border-zinc-300 rounded-md px-3 py-2 text-xs text-zinc-900 focus:outline-none focus:border-emerald-700">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="bg-emerald-700 hover:bg-emerald-800 text-white py-2 px-4 rounded-md text-xs font-semibold transition-colors">
                    Simpan Tugas
                </button>
            </div>
        </form>
    </div>

    <!-- Daftar Tugas Interaktif (Status, Edit & Hapus) -->
    <div class="mt-6 border border-zinc-200 rounded-lg bg-white overflow-hidden">
        <div class="px-5 py-3.5 border-b border-zinc-200 bg-zinc-50/70 flex items-center justify-between">
            <h2 class="text-xs font-semibold uppercase tracking-wider text-zinc-600">Daftar Kerja</h2>
            <span class="text-xs font-mono text-zinc-400 tabular-nums">{{ $daftarTugas->count() }} item</span>
        </div>

        <div class="divide-y divide-zinc-200">
            @forelse($daftarTugas as $tugas)
                <div class="p-4 sm:px-5 hover:bg-zinc-50/50 transition-colors">
                    <!-- Tampilan Utama Baris Tugas -->
                    <div id="view-tugas-{{ $tugas->id }}" class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="text-sm font-semibold {{ $tugas->status === 'done' ? 'line-through text-zinc-400' : 'text-zinc-900' }}">
                                    {{ $tugas->judul_tugas }}
                                </p>
                               <span class="text-[11px] font-mono px-2 py-0.5 rounded bg-zinc-100 text-zinc-600 tabular-nums">
                                Target: {{ \Carbon\Carbon::parse($tugas->bulan_target)->translatedFormat('d M Y') }}
                            </span>
                            </div>

                            @if($tugas->deskripsi)
                                <p class="text-xs text-zinc-600 mt-1.5 leading-relaxed whitespace-pre-line">{{ $tugas->deskripsi }}</p>
                            @else
                                <p class="text-[11px] text-zinc-400 mt-1">Tidak ada deskripsi tambahan.</p>
                            @endif
                        </div>

                        <!-- Kontrol Aksi: Ubah Status, Edit, Hapus -->
                        <div class="flex items-center gap-2 shrink-0">
                            <form action="{{ route('pegawai.tugas.update', $tugas) }}" method="POST">
                                @csrf @method('PATCH')
                                <select name="status" onchange="this.form.submit()"
                                        class="border border-zinc-300 rounded-md px-2.5 py-1.5 text-xs font-medium bg-white text-zinc-800 focus:outline-none focus:border-emerald-700">
                                    <option value="todo" @selected($tugas->status === 'todo')>To Do</option>
                                    <option value="in_progress" @selected($tugas->status === 'in_progress')>In Progress</option>
                                    <option value="done" @selected($tugas->status === 'done')>Done</option>
                                </select>
                            </form>

                            <button type="button" onclick="toggleEditTugas({{ $tugas->id }})"
                                    class="px-2.5 py-1.5 rounded-md border border-zinc-500 bg-white hover:border-yellow-200 hover:text-yellow-500 text-xs font-medium text-zinc-700 transition-colors">
                                Edit
                            </button>

                            <form action="{{ route('pegawai.tugas.destroy', $tugas) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus tugas ini secara permanen?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="px-2.5 py-1.5 rounded-md border border-zinc-500 bg-white hover:border-red-300 hover:text-red-700 text-xs font-medium text-zinc-600 transition-colors">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Panel Form Edit Inline (Tersembunyi Secara Default) -->
                    <form id="edit-tugas-{{ $tugas->id }}"
                          action="{{ route('pegawai.tugas.edit', $tugas) }}"
                          method="POST"
                          onsubmit="return confirm('Apakah Anda yakin ingin menyimpan perubahan pada tugas ini?')"
                          class="hidden mt-3 pt-3 border-t border-zinc-200 space-y-3 bg-zinc-50 p-4 rounded-md">
                        @csrf @method('PUT')

                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                            <div class="sm:col-span-8">
                                <label class="block text-[11px] font-medium text-zinc-600 mb-1">Judul Tugas</label>
                                <input type="text" name="judul_tugas" value="{{ $tugas->judul_tugas }}" 
                                required class="w-full border border-zinc-300 rounded-md px-3 py-1.5 text-xs bg-white text-zinc-900 focus:outline-none focus:border-emerald-700">
                            </div>
                            <div class="sm:col-span-4">
                                <label class="block text-[11px] font-medium text-zinc-600 mb-1">Tanggal Target</label>
                                <input type="date" name="bulan_target" value="{{ $tugas->bulan_target }}" 
                                required class="w-full border border-zinc-300 rounded-md px-3 py-1.5 text-xs bg-white text-zinc-900 focus:outline-none focus:border-emerald-700">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-medium text-zinc-600 mb-1">Deskripsi Detail</label>
                            <textarea name="deskripsi" rows="2"
                                      class="w-full border border-zinc-300 rounded-md px-3 py-1.5 text-xs bg-white text-zinc-900 focus:outline-none focus:border-emerald-700">{{ $tugas->deskripsi }}</textarea>
                        </div>

                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="toggleEditTugas({{ $tugas->id }})"
                                    class="px-3 py-1.5 rounded-md border border-zinc-300 bg-white hover:bg-zinc-100 text-xs font-medium text-zinc-700">
                                Batal
                            </button>
                            <button type="submit"
                                    class="px-3.5 py-1.5 rounded-md bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            @empty
                <div class="px-5 py-10 text-center text-xs text-zinc-500">
                    Belum ada tugas bulanan yang ditambahkan.
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    function toggleEditTugas(id) {
        const viewEl = document.getElementById('view-tugas-' + id);
        const editEl = document.getElementById('edit-tugas-' + id);
        if (viewEl && editEl) {
            viewEl.classList.toggle('hidden');
            editEl.classList.toggle('hidden');
        }
    }
</script>
@endsection