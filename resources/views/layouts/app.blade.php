<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'HR Startup')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-50 text-zinc-900 antialiased min-h-screen flex flex-col">
    <nav class="bg-white border-b border-zinc-200 sticky top-0 z-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 h-14 flex justify-between items-center">
            <!-- Kiri: Identitas Aplikasi & Menu Desktop -->
            <div class="flex items-center gap-8">
                <a href="{{ auth()->check() ? (auth()->user()->isAdmin() ? route('admin.dashboard') : route('pegawai.dashboard')) : route('login') }}"
                   class="font-semibold text-sm tracking-tight text-zinc-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded bg-zinc-900 text-white flex items-center justify-center text-xs font-mono">HR</span>
                    <span>HR Startup</span>
                </a>

                @auth
                    @if(auth()->user()->isAdmin())
                        <div class="hidden md:flex items-center gap-5 text-xs font-medium text-zinc-600">
                            <a href="{{ route('admin.dashboard') }}" class="hover:text-zinc-900 {{ request()->routeIs('admin.dashboard') ? 'text-emerald-700 font-semibold' : '' }}">Dashboard</a>
                            <a href="{{ route('pegawai.index') }}" class="hover:text-zinc-900 {{ request()->routeIs('pegawai.*') ? 'text-emerald-700 font-semibold' : '' }}">Data Pegawai</a>
                            <a href="{{ route('admin.absensi') }}" class="hover:text-zinc-900 {{ request()->routeIs('admin.absensi') ? 'text-emerald-700 font-semibold' : '' }}">Absensi</a>
                            <a href="{{ route('admin.cuti') }}" class="hover:text-zinc-900 {{ request()->routeIs('admin.cuti') ? 'text-emerald-700 font-semibold' : '' }}">Cuti</a>
                            <a href="{{ route('admin.slip-gaji') }}" class="hover:text-zinc-900 {{ request()->routeIs('admin.slip-gaji') ? 'text-emerald-700 font-semibold' : '' }}">Slip Gaji</a>
                            <a href="{{ route('admin.struk') }}" class="hover:text-zinc-900 {{ request()->routeIs('admin.struk*') ? 'text-emerald-700 font-semibold' : '' }}">Pengajuan Struk</a>
                        </div>
                    @else
                        <div class="hidden md:flex items-center gap-5 text-xs font-medium text-zinc-600">
                            <a href="{{ route('pegawai.dashboard') }}" class="hover:text-zinc-900 {{ request()->routeIs('pegawai.dashboard') ? 'text-emerald-700 font-semibold' : '' }}">Ringkasan</a>
                            <a href="{{ route('pegawai.absensi.index') }}" class="hover:text-zinc-900 {{ request()->routeIs('pegawai.absensi.*') ? 'text-emerald-700 font-semibold' : '' }}">Absensi</a>
                            <a href="{{ route('pegawai.cuti.index') }}" class="hover:text-zinc-900 {{ request()->routeIs('pegawai.cuti.*') ? 'text-emerald-700 font-semibold' : '' }}">Ajuan Cuti</a>
                            <a href="{{ route('pegawai.slip-gaji.index') }}" class="hover:text-zinc-900 {{ request()->routeIs('pegawai.slip-gaji.*') ? 'text-emerald-700 font-semibold' : '' }}">Slip Gaji</a>
                            <a href="{{ route('pegawai.tugas.index') }}" class="hover:text-zinc-900 {{ request()->routeIs('pegawai.tugas.*') ? 'text-emerald-700 font-semibold' : '' }}">Tugas</a>
                            <a href="{{ route('pegawai.klaim.index') }}" class="block py-1.5 text-zinc-700 hover:text-emerald-700">Klaim Struk</a>
                            <a href="{{ route('pegawai.kudos.index') }}" class="block py-1.5 text-zinc-700 hover:text-emerald-700">Kirim Kudos</a>
                            <a href="{{ route('pegawai.profil.index') }}" class="block py-1.5 text-emerald-700 font-semibold">Edit Profil</a>                        
                        </div>
                    @endif
                @endauth
            </div>

            <!-- Kanan: Edit Profil / Role & Hamburger Mobile -->
            @auth
                <div class="hidden md:flex items-center gap-4">
                    @if(!auth()->user()->isAdmin())
                        <a href="{{ route('pegawai.profil.index') }}"
                           class="text-xs font-medium px-3 py-1.5 rounded-md border border-zinc-200 hover:border-zinc-300 text-zinc-700 transition-colors {{ request()->routeIs('pegawai.profil.*') ? 'bg-zinc-100 text-zinc-900 font-semibold' : 'bg-white' }}">
                            Edit Profil
                        </a>
                    @else
                        <span class="text-[11px] font-mono px-2 py-0.5 rounded bg-zinc-100 text-zinc-700 uppercase">ADMIN</span>
                    @endif

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs font-medium text-zinc-500 hover:text-zinc-900 transition-colors">
                            Keluar
                        </button>
                    </form>
                </div>

                <!-- Tombol Hamburger Ponsel -->
                <button type="button" id="mobileMenuBtn"
                        class="md:hidden p-1.5 rounded-md border border-zinc-200 text-zinc-700 hover:bg-zinc-100 focus:outline-none"
                        aria-label="Buka Navigasi">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            @endauth
        </div>

        <!-- Panel Navigasi Ponsel -->
        @auth
            <div id="mobileMenuPanel" class="hidden md:hidden border-t border-zinc-200 bg-white px-4 py-3 space-y-1.5 text-xs font-medium">
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block py-1.5 text-zinc-700 hover:text-emerald-700">Dashboard</a>
                    <a href="{{ route('pegawai.index') }}" class="block py-1.5 text-zinc-700 hover:text-emerald-700">Data Pegawai</a>
                    <a href="{{ route('admin.absensi') }}" class="block py-1.5 text-zinc-700 hover:text-emerald-700">Absensi</a>
                    <a href="{{ route('admin.cuti') }}" class="block py-1.5 text-zinc-700 hover:text-emerald-700">Cuti</a>
                    <a href="{{ route('admin.slip-gaji') }}" class="block py-1.5 text-zinc-700 hover:text-emerald-700">Slip Gaji</a>
                    <a href="{{ route('admin.struk') }}" class="hover:text-zinc-900 {{ request()->routeIs('admin.struk*') ? 'text-emerald-700 font-semibold' : '' }}">Pengajuan Struk</a>
                @else
                    <a href="{{ route('pegawai.dashboard') }}" class="block py-1.5 text-zinc-900 font-semibold">Ringkasan</a>
                    <a href="{{ route('pegawai.absensi.index') }}" class="block py-1.5 text-zinc-700 hover:text-emerald-700">Absensi</a>
                    <a href="{{ route('pegawai.cuti.index') }}" class="block py-1.5 text-zinc-700 hover:text-emerald-700">Ajuan Cuti</a>
                    <a href="{{ route('pegawai.slip-gaji.index') }}" class="block py-1.5 text-zinc-700 hover:text-emerald-700">Slip Gaji</a>
                    <a href="{{ route('pegawai.tugas.index') }}" class="block py-1.5 text-zinc-700 hover:text-emerald-700">Daftar Tugas</a>
                    <a href="{{ route('pegawai.klaim.index') }}" class="block py-1.5 text-zinc-700 hover:text-emerald-700">Klaim Struk</a>
                    <a href="{{ route('pegawai.kudos.index') }}" class="block py-1.5 text-zinc-700 hover:text-emerald-700">Kirim Kudos</a>
                    <a href="{{ route('pegawai.profil.index') }}" class="block py-1.5 text-emerald-700 font-semibold">Edit Profil</a>
                @endif

                <div class="pt-2 border-t border-zinc-100">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-left py-1.5 text-zinc-500 hover:text-zinc-900">Keluar</button>
                    </form>
                </div>
            </div>
        @endauth
    </nav>

    <main class="max-w-5xl w-full mx-auto px-4 sm:px-6 py-6 sm:py-8 flex-1">
        @if(session('success'))
            <div class="mb-5 px-4 py-3 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-5 px-4 py-3 rounded-md bg-red-50 border border-red-200 text-red-800 text-xs font-medium">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-5 px-4 py-3 rounded-md bg-red-50 border border-red-200 text-red-800 text-xs">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <script>
        const btn = document.getElementById('mobileMenuBtn');
        const panel = document.getElementById('mobileMenuPanel');
        if (btn && panel) {
            btn.addEventListener('click', () => panel.classList.toggle('hidden'));
        }
    </script>
</body>
</html>