@extends('layouts.app')
@section('title', 'Pengaturan Profil — ' . $pegawai->nama_lengkap)

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="pb-5 border-b border-zinc-200 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <a href="{{ route('pegawai.dashboard') }}" class="inline-block text-xs font-medium text-zinc-500 hover:text-zinc-900 mb-2">
                &larr; Kembali ke Ringkasan
            </a>
            <h1 class="text-xl font-semibold tracking-tight text-zinc-900">Pengaturan Profil & Akun</h1>
            <p class="text-xs text-zinc-500 mt-0.5">Kelola identitas kepegawaian, alamat email login, dan kata sandi</p>
        </div>

        <div class="flex items-center gap-2 text-xs font-mono text-zinc-600 bg-white px-3 py-1.5 rounded-md border border-zinc-200 self-start sm:self-auto">
            <span>{{ $pegawai->kode_pegawai }}</span>
            <span class="text-zinc-300">|</span>
            <span>{{ $pegawai->jabatan }}</span>
        </div>
    </div>

    <div class="mt-6 space-y-6">
        <!-- BAGIAN 1: DATA PROFIL & EMAIL LOGIN -->
        <div class="border border-zinc-200 rounded-lg bg-white overflow-hidden">
            <div class="px-5 py-3.5 border-b border-zinc-200 bg-zinc-50/70">
                <h2 class="text-xs font-semibold uppercase tracking-wider text-zinc-600">Identitas Pribadi & Email Akun</h2>
            </div>

            <form action="{{ route('pegawai.profil.update') }}"
                  method="POST"
                  onsubmit="return confirm('Simpan perubahan pada profil dan email akun Anda?')"
                  class="p-5 sm:p-6 space-y-5">
                @csrf @method('PUT')

                <!-- Baris 1: Nama Lengkap & Email Login -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-zinc-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $pegawai->nama_lengkap) }}" required
                               class="w-full border border-zinc-300 rounded-md px-3 py-2 text-xs text-zinc-900 focus:outline-none focus:border-emerald-700">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-zinc-700 mb-1">Email Akun Login</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                               class="w-full border border-zinc-300 rounded-md px-3 py-2 text-xs text-zinc-900 focus:outline-none focus:border-emerald-700">
                    </div>
                </div>

                <!-- Baris 2: Jenis Kelamin, Umur, Pendidikan Terakhir -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-zinc-700 mb-1">Jenis Kelamin</label>
                        <select name="jenis_kelamin" required
                                class="w-full border border-zinc-300 rounded-md px-3 py-2 text-xs bg-white text-zinc-900 focus:outline-none focus:border-emerald-700">
                            <option value="Laki-laki" @selected(old('jenis_kelamin', $pegawai->jenis_kelamin) === 'Laki-laki')>Laki-laki</option>
                            <option value="Perempuan" @selected(old('jenis_kelamin', $pegawai->jenis_kelamin) === 'Perempuan')>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-zinc-700 mb-1">Umur (Tahun)</label>
                        <input type="number" name="umur" value="{{ old('umur', $pegawai->umur) }}" min="18" max="65" required
                               class="w-full border border-zinc-300 rounded-md px-3 py-2 text-xs text-zinc-900 tabular-nums focus:outline-none focus:border-emerald-700">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-zinc-700 mb-1">Pendidikan Terakhir</label>
                        <select name="pendidikan_terakhir" required
                                class="w-full border border-zinc-300 rounded-md px-3 py-2 text-xs bg-white text-zinc-900 focus:outline-none focus:border-emerald-700">
                            @foreach(['SMA/SMK', 'D3', 'S1', 'S2'] as $jenjang)
                                <option value="{{ $jenjang }}" @selected(old('pendidikan_terakhir', $pegawai->pendidikan_terakhir) === $jenjang)>{{ $jenjang }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Baris 3: Alamat Domisili -->
                <div>
                    <label class="block text-xs font-medium text-zinc-700 mb-1">Alamat Domisili</label>
                    <textarea name="alamat" rows="2" required
                              class="w-full border border-zinc-300 rounded-md px-3 py-2 text-xs text-zinc-900 focus:outline-none focus:border-emerald-700">{{ old('alamat', $pegawai->alamat ?? '') }}</textarea>
                </div>

                <div class="pt-2 border-t border-zinc-100 flex justify-end">
                    <button type="submit"
                            class="bg-emerald-700 hover:bg-emerald-800 text-white px-4 py-2 rounded-md text-xs font-semibold transition-colors">
                        Simpan Perubahan Profil
                    </button>
                </div>
            </form>
        </div>

        <!-- BAGIAN 2: KEAMANAN & GANTI PASSWORD -->
        <div class="border border-zinc-200 rounded-lg bg-white overflow-hidden">
            <div class="px-5 py-3.5 border-b border-zinc-200 bg-zinc-50/70">
                <h2 class="text-xs font-semibold uppercase tracking-wider text-zinc-600">Keamanan & Kata Sandi</h2>
            </div>

            <form action="{{ route('pegawai.password.update') }}"
                  method="POST"
                  onsubmit="return confirm('Anda yakin ingin mengubah password akun?')"
                  class="p-5 sm:p-6 space-y-4">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-zinc-700 mb-1">Password Saat Ini</label>
                        <input type="password" name="current_password" required
                               class="w-full border border-zinc-300 rounded-md px-3 py-2 text-xs text-zinc-900 focus:outline-none focus:border-emerald-700">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-zinc-700 mb-1">Password Baru (Min. 8 Karakter)</label>
                        <input type="password" name="password" required
                               class="w-full border border-zinc-300 rounded-md px-3 py-2 text-xs text-zinc-900 focus:outline-none focus:border-emerald-700">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-zinc-700 mb-1">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" required
                               class="w-full border border-zinc-300 rounded-md px-3 py-2 text-xs text-zinc-900 focus:outline-none focus:border-emerald-700">
                    </div>
                </div>

                <div class="pt-2 border-t border-zinc-100 flex justify-end">
                    <button type="submit"
                            class="bg-emerald-700 hover:bg-emerald-800 text-white px-4 py-2 rounded-md text-xs font-semibold transition-colors">
                        Perbarui Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection