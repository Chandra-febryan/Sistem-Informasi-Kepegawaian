@extends('layouts.app')
@section('title', 'Data Master Pegawai')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-slate-900">Data Master Pegawai</h1>
    <a href="{{ route('pegawai.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
        + Daftarkan Pegawai Baru
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-x-auto">
    <table class="w-full text-left text-sm">
        <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase text-xs">
            <tr>
                <th class="px-5 py-3">Kode</th>
                <th class="px-5 py-3">Nama Lengkap</th>
                <th class="px-5 py-3">Email</th>
                <th class="px-5 py-3">Gender</th>
                <th class="px-5 py-3">Umur</th>
                <th class="px-5 py-3">Alamat</th>
                <th class="px-5 py-3">Pendidikan</th>
                <th class="px-5 py-3">Jabatan</th>
                <th class="px-5 py-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
            @forelse($pegawais as $p)
                <tr class="hover:bg-slate-50">
                    <td class="px-5 py-3 font-mono text-xs font-semibold text-indigo-600">{{ $p->kode_pegawai }}</td>
                    <td class="px-5 py-3 font-medium text-slate-900">{{ $p->nama_lengkap }}</td>
                    <td class="px-5 py-3 text-slate-500">{{ $p->user->email ?? '-' }}</td>
                    <td class="px-5 py-3">{{ $p->jenis_kelamin }}</td>
                    <td class="px-5 py-3">{{ $p->umur }} thn</td>
                    <td class="px-5 py-3">{{$p->alamat}}</td>
                    <td class="px-5 py-3">{{ $p->pendidikan_terakhir }}</td>
                    <td class="px-5 py-3">{{ $p->jabatan }}</td>
                    <td class="px-5 py-3 text-right flex justify-end gap-2">
                        <a href="{{ route('pegawai.edit', $p) }}" class="text-amber-600 hover:underline font-medium">Edit</a>
                        <form action="{{ route('pegawai.destroy', $p) }}" method="POST" onsubmit="return confirm('Hapus pegawai ini beserta akun loginnya?')">
                            @csrf @method('DELETE')
                            <button class="text-rose-600 hover:underline font-medium">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="px-5 py-8 text-center text-slate-400">Belum ada data pegawai.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection