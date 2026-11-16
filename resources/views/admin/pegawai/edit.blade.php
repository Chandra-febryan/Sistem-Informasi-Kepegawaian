<!-- resources/views/admin/pegawai/edit.blade.php -->
@extends('layouts.app')
@section('title', 'Edit Data Pegawai')

@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded-xl border border-slate-200 shadow-sm">
    <h1 class="text-xl font-bold text-slate-900 mb-6">Edit Data Pegawai ({{ $pegawai->kode_pegawai }})</h1>

    <form action="{{ route('pegawai.update', $pegawai) }}" method="POST" class="space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-xs font-medium text-slate-600 mb-1">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $pegawai->nama_lengkap) }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-600 mb-1">Jabatan</label>
            <input type="text" name="jabatan" value="{{ old('jabatan', $pegawai->jabatan) }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
        </div>
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="w-full border rounded-lg px-3 py-2 text-sm bg-white">
                    <option value="Laki-laki" @selected($pegawai->jenis_kelamin === 'Laki-laki')>Laki-laki</option>
                    <option value="Perempuan" @selected($pegawai->jenis_kelamin === 'Perempuan')>Perempuan</option>
                </select>
            </div>
            
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Umur</label>
                <input type="number" name="umur" value="{{ old('umur', $pegawai->umur) }}" min="18" max="65" required class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
    <label class="block text-xs font-medium text-slate-600 mb-1">Pendidikan</label>
    <select name="pendidikan_terakhir" class="w-full border rounded-lg px-3 py-2 text-sm bg-white">
        @foreach(['SMA/SMK', 'D3', 'S1', 'S2'] as $jenjang)
            <option value="{{ $jenjang }}" @selected(old('pendidikan_terakhir', 'S1') === $jenjang)>{{ $jenjang }}</option>
        @endforeach
    </select>
</div>

        </div>
        <div>
    <label class="block text-xs font-medium text-slate-600 mb-1">Alamat Domisili</label>
    <textarea name="alamat" rows="2" value="{{old('alamat',$pegawai->alamat)}}" required class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm">{{ old('alamat') }}</textarea>
</div>
        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('pegawai.index') }}" class="px-4 py-2 border rounded-lg text-sm">Batal</a>
            <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm font-medium">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection