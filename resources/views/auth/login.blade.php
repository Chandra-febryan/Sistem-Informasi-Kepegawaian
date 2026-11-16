@extends('layouts.app')
@section('title', 'Masuk — HR Startup')

@section('content')
<div class="max-w-4xl mx-auto mt-2 sm:mt-8">
    <div class="grid grid-cols-1 lg:grid-cols-12 border border-zinc-200 rounded-lg bg-white overflow-hidden">
        <!-- SISI KIRI: Panel Sambutan & Geometris Monokrom (5 Kolom) -->
        <div class="lg:col-span-5 relative bg-zinc-900 text-zinc-100 p-7 sm:p-9 flex flex-col justify-between overflow-hidden border-b lg:border-b-0 lg:border-r border-zinc-800">
            <!-- Aksen Geometris Halus (Terinspirasi Referensi, Tanpa Gradasi Neon) -->
            <div class="pointer-events-none absolute -top-16 -left-16 w-64 h-64 rounded-full bg-zinc-800/70 border border-zinc-700/60"></div>
            <div class="pointer-events-none absolute -bottom-14 -right-10 w-44 h-44 rounded-full bg-zinc-800/80 border border-zinc-700/50"></div>

            <div class="relative z-10">
                <div class="inline-flex items-center gap-2 text-[11px] font-mono uppercase tracking-wider text-zinc-400">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>HR Startup Portal</span>
                </div>

                <h1 class="text-2xl font-bold tracking-tight text-white mt-5">
                    SELAMAT DATANG
                </h1>
                <p class="text-xs font-mono uppercase tracking-wider text-emerald-400 mt-1">
                    Sistem Manajemen Pegawai
                </p>
                <p class="text-xs text-zinc-400 mt-3 leading-relaxed">
                    Kelola presensi harian, pengajuan cuti, target tugas bulanan, dan rincian slip gaji dalam satu ruang kerja terpusat.
                </p>
            </div>
        </div>

        <!-- SISI KANAN: Form Masuk Akun (7 Kolom) -->
        <div class="lg:col-span-7 relative p-7 sm:p-9 flex flex-col justify-center overflow-hidden">
            <!-- Aksen Lingkaran Sudut Kanan Bawah -->
            <div class="pointer-events-none absolute -bottom-12 -right-12 w-32 h-32 rounded-full bg-zinc-100 border border-zinc-200/80"></div>

            <div class="relative z-10 max-w-sm w-full mx-auto">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold tracking-tight text-zinc-900">Masuk ke Portal</h2>
                    <p class="text-xs text-zinc-500 mt-1">Masukkan email dan kata sandi akun kepegawaian Anda.</p>
                </div>

                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Input Email -->
                    <div>
                        <label for="email" class="block text-xs font-medium text-zinc-700 mb-1">Alamat Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-zinc-400 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </span>
                            <input id="email" type="email" name="email" value="{{ old('email') }}"
                                   placeholder="nama@perusahaan.id" required autofocus
                                   class="w-full border border-zinc-300 bg-zinc-50/70 focus:bg-white rounded-md pl-9 pr-3.5 py-2.5 text-xs text-zinc-900 placeholder:text-zinc-400 focus:outline-none focus:border-emerald-700 transition-colors">
                        </div>
                    </div>

                    <!-- Input Password + Toggle Tampilkan -->
                    <div>
                        <label for="password" class="block text-xs font-medium text-zinc-700 mb-1">Kata Sandi</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-zinc-400 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </span>
                            <input id="password" type="password" name="password"
                                   placeholder="Masukkan kata sandi" required
                                   class="w-full border border-zinc-300 bg-zinc-50/70 focus:bg-white rounded-md pl-9 pr-20 py-2.5 text-xs text-zinc-900 placeholder:text-zinc-400 focus:outline-none focus:border-emerald-700 transition-colors">
                            <button type="button" onclick="toggleLoginPassword()" id="togglePassBtn"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-[11px] font-mono font-semibold text-zinc-500 hover:text-zinc-900">
                                LIHAT
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-xs pt-0.5">
                        <label class="inline-flex items-center gap-2 text-zinc-600 cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded border-zinc-300 text-emerald-700 focus:ring-emerald-700">
                            <span>Ingat sesi saya</span>
                        </label>
                    </div>

                    <button type="submit"
                            class="w-full bg-emerald-700 hover:bg-emerald-800 text-white py-2.5 px-4 rounded-md text-xs font-semibold transition-colors">
                        Masuk
                    </button>
                </form>

                <!-- Pemisah & Navigasi ke Registrasi -->
                <div class="relative my-5">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-zinc-200"></div>
                    </div>
                    <div class="relative flex justify-center text-[11px]">
                        <span class="bg-white px-2.5 text-zinc-400 font-mono">ATAU</span>
                    </div>
                </div>

                <a href="{{ route('register') }}"
                   class="block w-full text-center border border-zinc-300 hover:border-zinc-900 bg-white hover:bg-zinc-50 text-zinc-900 py-2.5 px-4 rounded-md text-xs font-semibold transition-colors">
                    Daftar Akun Pegawai Baru
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleLoginPassword() {
        const input = document.getElementById('password');
        const btn = document.getElementById('togglePassBtn');
        if (input.type === 'password') {
            input.type = 'text';
            btn.textContent = 'TUTUP';
        } else {
            input.type = 'password';
            btn.textContent = 'LIHAT';
        }
    }
</script>
@endsection