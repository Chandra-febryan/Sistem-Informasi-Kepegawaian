@extends('layouts.app')
@section('title', 'Daftar Pegawai — HR Startup')

@section('content')
<div class="max-w-4xl mx-auto mt-2 sm:mt-6">
    <div class="grid grid-cols-1 lg:grid-cols-12 border border-zinc-200 rounded-lg bg-white overflow-hidden">
        <!-- SISI KIRI: Form Pengisian Registrasi (8 Kolom) -->
        <div class="lg:col-span-8 relative p-6 sm:p-8 order-2 lg:order-1 overflow-hidden">
            <div class="mb-5 pb-4 border-b border-zinc-100">
                <h1 class="text-xl font-semibold tracking-tight text-zinc-900">Buat Akun Pegawai</h1>
                <p class="text-xs text-zinc-500 mt-1">Lengkapi biodata kepegawaian di bawah ini untuk mengaktifkan akun.</p>
            </div>

            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Baris 1: Kode Pegawai (Otomatis Readonly) & Nama Lengkap -->
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                    <div class="sm:col-span-4">
                        <label class="block text-xs font-medium text-zinc-700 mb-1">Kode Pegawai</label>
                        <input type="text" name="kode_pegawai" value="{{ $kodeOtomatis }}" readonly tabindex="-1"
                               class="w-full border border-zinc-200 bg-zinc-100 text-zinc-600 font-mono font-semibold rounded-md px-3 py-2 text-xs cursor-not-allowed select-none">
                    </div>

                    <div class="sm:col-span-8">
                        <label for="nama_lengkap" class="block text-xs font-medium text-zinc-700 mb-1">Nama Lengkap</label>
                        <input id="nama_lengkap" type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                               placeholder="Nama lengkap sesuai identitas" required autofocus
                               class="w-full border border-zinc-300 bg-zinc-50/70 focus:bg-white rounded-md px-3 py-2 text-xs text-zinc-900 focus:outline-none focus:border-emerald-700 transition-colors">
                    </div>
                </div>

                <!-- Baris 2: Jenis Kelamin, Umur, Pendidikan Terakhir -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="jenis_kelamin" class="block text-xs font-medium text-zinc-700 mb-1">Jenis Kelamin</label>
                        <select id="jenis_kelamin" name="jenis_kelamin" required
                                class="w-full border border-zinc-300 bg-zinc-50/70 focus:bg-white rounded-md px-3 py-2 text-xs text-zinc-900 focus:outline-none focus:border-emerald-700">
                            <option value="Laki-laki" @selected(old('jenis_kelamin') === 'Laki-laki')>Laki-laki</option>
                            <option value="Perempuan" @selected(old('jenis_kelamin') === 'Perempuan')>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label for="umur" class="block text-xs font-medium text-zinc-700 mb-1">Umur (Tahun)</label>
                        <input id="umur" type="number" name="umur" value="{{ old('umur') }}" min="18" max="65"
                               placeholder="24" required
                               class="w-full border border-zinc-300 bg-zinc-50/70 focus:bg-white rounded-md px-3 py-2 text-xs text-zinc-900 tabular-nums focus:outline-none focus:border-emerald-700">
                    </div>

                    <div>
                        <label for="pendidikan_terakhir" class="block text-xs font-medium text-zinc-700 mb-1">Pendidikan Terakhir</label>
                        <select id="pendidikan_terakhir" name="pendidikan_terakhir" required
                                class="w-full border border-zinc-300 bg-zinc-50/70 focus:bg-white rounded-md px-3 py-2 text-xs text-zinc-900 focus:outline-none focus:border-emerald-700">
                            @foreach(['SMA/SMK', 'D3', 'S1', 'S2'] as $jenjang)
                                <option value="{{ $jenjang }}" @selected(old('pendidikan_terakhir', 'S1') === $jenjang)>{{ $jenjang }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Baris 3: Alamat Domisili -->
                <div>
                    <label for="alamat" class="block text-xs font-medium text-zinc-700 mb-1">Alamat Domisili</label>
                    <textarea id="alamat" name="alamat" rows="2" placeholder="Alamat tempat tinggal saat ini..." required
                              class="w-full border border-zinc-300 bg-zinc-50/70 focus:bg-white rounded-md px-3 py-2 text-xs text-zinc-900 focus:outline-none focus:border-emerald-700">{{ old('alamat') }}</textarea>
                </div>

                <!-- Baris 4: Email Login -->
                <div class="pt-2 border-t border-zinc-100">
                    <label for="email" class="block text-xs font-medium text-zinc-700 mb-1">Alamat Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           placeholder="nama@perusahaan.id" required
                           class="w-full border border-zinc-300 bg-zinc-50/70 focus:bg-white rounded-md px-3 py-2 text-xs text-zinc-900 focus:outline-none focus:border-emerald-700">
                </div>

                <!-- Baris 5: Password & Konfirmasi -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-medium text-zinc-700 mb-1">Kata Sandi (Min. 8 Karakter)</label>
                        <input id="password" type="password" name="password" placeholder="••••••••" required
                               class="w-full border border-zinc-300 bg-zinc-50/70 focus:bg-white rounded-md px-3 py-2 text-xs text-zinc-900 focus:outline-none focus:border-emerald-700">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-medium text-zinc-700 mb-1">Konfirmasi Kata Sandi</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" placeholder="••••••••" required
                               class="w-full border border-zinc-300 bg-zinc-50/70 focus:bg-white rounded-md px-3 py-2 text-xs text-zinc-900 focus:outline-none focus:border-emerald-700">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full bg-emerald-700 hover:bg-emerald-800 text-white py-2.5 px-4 rounded-md text-xs font-semibold transition-colors">
                        Daftar & Masuk Portal
                    </button>
                </div>
            </form>
        </div>

        <!-- SISI KANAN: Panel Informasi & Kode Induk Otomatis (4 Kolom) -->
        <div class="lg:col-span-4 relative bg-zinc-900 text-zinc-100 p-7 sm:p-8 flex flex-col justify-between order-1 lg:order-2 overflow-hidden border-b lg:border-b-0 lg:border-l border-zinc-800">
            <!-- Aksen Geometris Halus -->
            <div class="pointer-events-none absolute -top-14 -right-14 w-56 h-56 rounded-full bg-zinc-800/70 border border-zinc-700/60"></div>
            <div class="pointer-events-none absolute -bottom-16 -left-10 w-44 h-44 rounded-full bg-zinc-800/80 border border-zinc-700/50"></div>

            <div class="relative z-10">
                <div class="inline-flex items-center gap-2 text-[11px] font-mono uppercase tracking-wider text-zinc-400">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Pendaftaran Baru</span>
                </div>

                <h2 class="text-xl font-bold tracking-tight text-white mt-4">
                    BERGABUNG DENGAN TIM
                </h2>
                <p class="text-xs text-zinc-400 mt-2 leading-relaxed">
                    Nomor induk kepegawaian ditetapkan secara otomatis berurutan untuk mencegah duplikasi data.
                </p>

                <!-- Kartu Kode Pegawai Otomatis -->
                <div class="mt-6 p-3.5 rounded-md bg-zinc-800/90 border border-zinc-700">
                    <span class="block text-[10px] font-mono uppercase tracking-wider text-zinc-400">
                        Nomor Induk Anda
                    </span>
                    <span class="block text-xl font-mono font-bold text-emerald-400 mt-0.5 tabular-nums">
                        {{ $kodeOtomatis }}
                    </span>
                </div>
            </div>

                <a href="{{ route('login') }}"
                   class="block w-full text-center border border-zinc-700 hover:border-zinc-500 bg-zinc-800/60 hover:bg-zinc-800 text-zinc-200 py-2 px-3 rounded-md text-xs font-medium transition-colors">
                    &larr; Kembali ke Login
                </a>
            </div>
        </div>
    </div>
</div>
@endsection