<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Cuti;
use App\Models\KlaimStruk;
use App\Models\Pegawai;
use App\Models\SlipGaji;
use Illuminate\Http\Request;

class AdminOperasionalController extends Controller
{
    public function absensi(Request $request)
    {
        $tanggal = $request->input('tanggal', today()->toDateString());
        $absensis = Absensi::with('pegawai')
            ->whereDate('tanggal', $tanggal)
            ->latest()
            ->get();

        return view('admin.operasional.absensi', compact('absensis', 'tanggal'));
    }

    public function cuti()
    {
        $cutis = Cuti::with('pegawai')->latest()->get();

        return view('admin.operasional.cuti', compact('cutis'));
    }

    public function updateStatusCuti(Request $request, Cuti $cuti)
    {
        $validated = $request->validate([
            'status' => 'required|in:disetujui,ditolak',
        ]);

        $cuti->update($validated);

        return back()->with('success', 'Status pengajuan cuti berhasil diperbarui.');
    }

    public function slipGaji()
    {
        $pegawais = Pegawai::orderBy('nama_lengkap')->get();
        $slipGajis = SlipGaji::with('pegawai')->latest()->get();

        return view('admin.operasional.slipgaji', compact('pegawais', 'slipGajis'));
    }

    public function storeSlipGaji(Request $request)
    {
        $validated = $request->validate([
            'pegawai_id'    => 'required|exists:pegawai,id',
            'periode_bulan' => 'required|string|max:20',
            'gaji_pokok'    => 'required|integer|min:0',
            'tunjangan'     => 'nullable|integer|min:0',
            'potongan'      => 'nullable|integer|min:0',
        ]);

        $tunjangan = $validated['tunjangan'] ?? 0;
        $potongan  = $validated['potongan'] ?? 0;
        $totalGaji = ($validated['gaji_pokok'] + $tunjangan) - $potongan;

        SlipGaji::create([
            'pegawai_id'    => $validated['pegawai_id'],
            'periode_bulan' => $validated['periode_bulan'],
            'gaji_pokok'    => $validated['gaji_pokok'],
            'tunjangan'     => $tunjangan,
            'potongan'      => $potongan,
            'total_gaji'    => $totalGaji,
        ]);

        return back()->with('success', 'Slip gaji pegawai berhasil diterbitkan.');
    }

    public function destroySlipGaji(SlipGaji $slipGaji)
    {
        $slipGaji->delete();

        return back()->with('success', 'Slip gaji berhasil dihapus.');
    }

    public function struk()
    {
        $klaims = KlaimStruk::with('pegawai')->latest()->get();

        return view('admin.operasional.struk', compact('klaims'));
    }

    public function updateStatusStruk(Request $request, KlaimStruk $klaim)
    {
        $validated = $request->validate([
            'status' => 'required|in:disetujui,ditolak',
        ]);

        $klaim->update($validated);

        return back()->with('success', 'Status klaim struk berhasil diperbarui.');
    }
}