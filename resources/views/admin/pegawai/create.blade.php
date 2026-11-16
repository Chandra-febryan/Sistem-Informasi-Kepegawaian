@extends('layouts.app')
@section('title', 'Tambah Pegawai Baru')

@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded-xl border border-slate-200 shadow-sm">
    <h1 class="text-xl font-bold text-slate-900 mb-1">Daftarkan Pegawai Baru</h1>
    <p class="text-xs text-slate-500 mb-6">
        Akun login pegawai akan otomatis dibuat dengan password default: <strong class="text-slate-700">Pegawai#123</strong>
    </p>

    <form action="{{ route('pegawai.store') }}" method="POST" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Kode Pegawai (Otomatis)</label>
                <input type="text" name="kode_pegawai" value="{{ $kodeOtomatis }}" readonly
                       class="w-full border border-slate-300 bg-slate-100 text-slate-600 font-mono font-semibold rounded-lg px-3.5 py-2 text-sm cursor-not-allowed">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Jabatan</label>
                <input type="text" name="jabatan" value="{{ old('jabatan', 'Staff') }}" required
                       class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:border-indigo-500">
            </div>
        </div>

        <div>
            <label class="block text-xs font-medium text-slate-600 mb-1">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                   class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:border-indigo-500">
        </div>

        <div>
            <label class="block text-xs font-medium text-slate-600 mb-1">Email Akun Login</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="pegawai@startup.id" required
                   class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:border-indigo-500">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Jenis Kelamin</label>
                <select name="jenis_kelamin" required
                        class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:border-indigo-500">
                    <option value="Laki-laki" @selected(old('jenis_kelamin') === 'Laki-laki')>Laki-laki</option>
                    <option value="Perempuan" @selected(old('jenis_kelamin') === 'Perempuan')>Perempuan</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Umur (Tahun)</label>
                <input type="number" name="umur" value="{{ old('umur') }}" min="18" max="65" required
                       class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Pendidikan Terakhir</label>
                <select name="pendidikan_terakhir" required
                        class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:border-indigo-500">
                    @foreach(['SMA/SMK', 'D3', 'S1', 'S2'] as $jenjang)
                        <option value="{{ $jenjang }}" @selected(old('pendidikan_terakhir', 'S1') === $jenjang)>{{ $jenjang }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-xs font-medium text-slate-600 mb-1">Alamat Domisili</label>
            <textarea name="alamat" rows="2" required
                      class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:border-indigo-500">{{ old('alamat') }}</textarea>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('pegawai.index') }}"
               class="px-4 py-2 border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-lg text-sm font-medium transition">
                Batal
            </a>
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg text-sm font-medium shadow-sm transition">
                Simpan Pegawai
            </button>
        </div>
    </form>
</div>
@endsection