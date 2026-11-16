@extends('layouts.app')
@section('title', 'Kirim Kudos')

@section('content')
<div class="max-w-3xl mx-auto">
    <a href="{{ route('pegawai.dashboard') }}" class="inline-flex items-center text-xs font-medium text-slate-500 hover:text-indigo-600 mb-4">
        &larr; Kembali ke Beranda
    </a>

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm mb-6">
        <h1 class="text-xl font-bold text-slate-900 mb-4">Kirim Kudos Apresiasi ke Rekan Tim</h1>
        <form action="{{ route('pegawai.kudos.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Pilih Rekan Tim</label>
                <select name="penerima_id" required class="w-full border border-slate-300 rounded-xl px-3.5 py-2 text-sm bg-white">
                    @foreach($rekanTim as $rekan)
                        <option value="{{ $rekan->id }}">{{ $rekan->nama_lengkap }} ({{ $rekan->jabatan }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Pesan Apresiasi</label>
                <textarea name="pesan" rows="2" placeholder="Terima kasih sudah membantu deployment tepat waktu!" required class="w-full border border-slate-300 rounded-xl px-3.5 py-2 text-sm"></textarea>
            </div>
            <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition">
                Kirim Kudos
            </button>
        </form>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-3">
        <h2 class="text-base font-semibold text-slate-800">Dinding Apresiasi Tim</h2>
        @forelse($feedKudos as $k)
            <div class="p-4 rounded-xl border border-pink-100 bg-pink-50/40 text-sm">
                <p class="font-semibold text-slate-900">
                    {{ $k->pengirim->nama_lengkap }} <span class="text-pink-600 font-normal">mengirim kudos ke</span> {{ $k->penerima->nama_lengkap }}
                </p>
                <p class="text-xs text-slate-600 mt-1">"{{ $k->pesan }}"</p>
                <p class="text-[11px] text-slate-400 mt-1.5">{{ $k->created_at->diffForHumans() }}</p>
            </div>
        @empty
            <p class="text-center text-slate-400 text-xs py-6">Belum ada kudos yang dikirim.</p>
        @endforelse
    </div>
</div>
@endsection