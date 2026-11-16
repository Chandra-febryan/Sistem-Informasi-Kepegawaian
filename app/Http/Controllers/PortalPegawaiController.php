<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Cuti;
use App\Models\Kudos;
use App\Models\Pegawai;
use App\Models\TugasBulanan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PortalPegawaiController extends Controller
{
    public function index()
    {
        $pegawai = auth()->user()->pegawai;

        // 1. Widget Absensi One-Tap
        $absensiHariIni = $pegawai->absensi()->whereDate('tanggal', today())->first();

        // 2. Ringkasan Saldo & Target
        $kuotaTahunan = 12;
        $cutiDisetujui = $pegawai->cuti()
            ->where('status', 'disetujui')
            ->whereYear('tanggal_mulai', now()->year)
            ->get();

        $hariTerpakai = $cutiDisetujui->sum(function ($cuti) {
            return Carbon::parse($cuti->tanggal_mulai)->diffInDays(Carbon::parse($cuti->tanggal_selesai)) + 1;
        });
        $sisaKuotaCuti = max(0, $kuotaTahunan - $hariTerpakai);

        $batasWellness = 2500000;
        $wellnessTerpakai = $pegawai->klaimStruks()
            ->where('status', '!=', 'ditolak')
            ->whereYear('created_at', now()->year)
            ->sum('nominal');
        $sisaWellness = max(0, $batasWellness - $wellnessTerpakai);

        $totalTugas = $pegawai->tugasBulanan()->count();
        $tugasSelesai = $pegawai->tugasBulanan()->where('status', 'done')->count();
        $persenOkr = $totalTugas > 0 ? round(($tugasSelesai / $totalTugas) * 100) : 0;

        // 3. Info Tim Hari Ini (Who's Out)
        $timCutiHariIni = Cuti::with('pegawai')
            ->where('status', 'disetujui')
            ->whereDate('tanggal_mulai', '<=', today())
            ->whereDate('tanggal_selesai', '>=', today())
            ->get();

        // 4. Ringkasan Kartu Grid
        $jumlahTugasAktif = $pegawai->tugasBulanan()->where('status', '!=', 'done')->count();
        $jumlahCutiPending = $pegawai->cuti()->where('status', 'pending')->count();
        $slipTerbaru = $pegawai->slipGaji()->latest()->first();

        return view('pegawai.dashboard', compact(
            'pegawai',
            'absensiHariIni',
            'sisaKuotaCuti',
            'kuotaTahunan',
            'sisaWellness',
            'batasWellness',
            'persenOkr',
            'timCutiHariIni',
            'jumlahTugasAktif',
            'jumlahCutiPending',
            'slipTerbaru'
        ));
    }

    public function halamanAbsensi()
    {
        $pegawai = auth()->user()->pegawai;
        $absensiHariIni = $pegawai->absensi()->whereDate('tanggal', today())->first();
        $riwayatAbsensi = $pegawai->absensi()->latest()->take(15)->get();

        return view('pegawai.absensi', compact('pegawai', 'absensiHariIni', 'riwayatAbsensi'));
    }

    public function halamanCuti()
    {
        $pegawai = auth()->user()->pegawai;
        $daftarCuti = $pegawai->cuti()->latest()->get();

        return view('pegawai.cuti', compact('pegawai', 'daftarCuti'));
    }

    public function halamanTugas()
    {
        $pegawai = auth()->user()->pegawai;
        $daftarTugas = $pegawai->tugasBulanan()->latest()->get();

        return view('pegawai.tugas', compact('pegawai', 'daftarTugas'));
    }

    public function halamanSlipGaji()
    {
        $pegawai = auth()->user()->pegawai;
        $slipGaji = $pegawai->slipGaji()->latest()->get();

        return view('pegawai.slip-gaji', compact('pegawai', 'slipGaji'));
    }

    public function halamanProfil()
    {
        $pegawai = auth()->user()->pegawai;

        return view('pegawai.profil', compact('pegawai'));
    }

    public function halamanKlaim()
    {
        $pegawai = auth()->user()->pegawai;
        $daftarKlaim = $pegawai->klaimStruks()->latest()->get();

        return view('pegawai.klaim-struk', compact('pegawai', 'daftarKlaim'));
    }

    public function halamanKudos()
    {
        $pegawai = auth()->user()->pegawai;
        $rekanTim = Pegawai::where('id', '!=', $pegawai->id)->orderBy('nama_lengkap')->get();
        $feedKudos = Kudos::with(['pengirim', 'penerima'])->latest()->take(20)->get();

        return view('pegawai.kudos', compact('pegawai', 'rekanTim', 'feedKudos'));
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'lokasi' => 'nullable|in:WFO,WFH',
        ]);

        $pegawai = auth()->user()->pegawai;
        $sudahAbsen = $pegawai->absensi()->whereDate('tanggal', today())->exists();

        if ($sudahAbsen) {
            return back()->with('error', 'Anda sudah melakukan clock-in hari ini.');
        }

        $jamSekarang = now()->format('H:i:s');
        $status = $jamSekarang > '09:15:00' ? 'terlambat' : 'hadir';
        $lokasi = $request->input('lokasi', 'WFO');

        Absensi::create([
            'pegawai_id' => $pegawai->id,
            'tanggal'    => today(),
            'jam_masuk'  => $jamSekarang,
            'lokasi'     => $lokasi,
            'status'     => $status,
        ]);

        return back()->with('success', "Clock-In ($lokasi) berhasil dicatat pukul $jamSekarang.");
    }

    public function checkOut()
    {
        $pegawai = auth()->user()->pegawai;
        $absensi = $pegawai->absensi()->whereDate('tanggal', today())->first();

        if (!$absensi || $absensi->jam_keluar) {
            return back()->with('error', 'Tidak dapat melakukan clock-out.');
        }

        $absensi->update(['jam_keluar' => now()->format('H:i:s')]);

        return back()->with('success', 'Clock-Out berhasil dicatat.');
    }

    public function ajukanCuti(Request $request)
    {
        $validated = $request->validate([
            'tanggal_mulai'   => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan'      => 'required|string|max:255',
        ]);

        auth()->user()->pegawai->cuti()->create($validated);

        return back()->with('success', 'Pengajuan cuti berhasil dikirim.');
    }

    public function storeTugas(Request $request)
    {
        $validated = $request->validate([
            'judul_tugas'  => 'required|string|max:150',
            'deskripsi'    => 'nullable|string',
            'bulan_target' => 'required|date',
        ]);

        auth()->user()->pegawai->tugasBulanan()->create($validated);

        return back()->with('success', 'Tugas bulanan berhasil ditambahkan.');
    }

    public function updateStatusTugas(Request $request, TugasBulanan $tugas)
    {
        abort_if($tugas->pegawai_id !== auth()->user()->pegawai->id, 403);

        $validated = $request->validate([
            'status' => 'required|in:todo,in_progress,done',
        ]);

        $tugas->update($validated);

        return back()->with('success', 'Status tugas diperbarui.');
    }

    public function updateTugas(Request $request, TugasBulanan $tugas)
    {
        abort_if($tugas->pegawai_id !== auth()->user()->pegawai->id, 403);

        $validated = $request->validate([
            'judul_tugas'  => 'required|string|max:150',
            'deskripsi'    => 'nullable|string|max:500',
            'bulan_target' => 'required|date',
        ]);

        $tugas->update($validated);

        return back()->with('success', 'Detail tugas berhasil diperbarui.');
    }

    public function destroyTugas(TugasBulanan $tugas)
    {
        abort_if($tugas->pegawai_id !== auth()->user()->pegawai->id, 403);

        $tugas->delete();

        return back()->with('success', 'Tugas berhasil dihapus.');
    }

    public function storeKlaim(Request $request)
    {
        $validated = $request->validate([
            'kategori'   => 'required|string|max:50',
            'keterangan' => 'required|string|max:200',
            'nominal'    => 'required|integer|min:1000',
        ]);

        auth()->user()->pegawai->klaimStruks()->create($validated);

        return back()->with('success', 'Klaim struk berhasil diajukan.');
    }

    public function storeKudos(Request $request)
    {
        $validated = $request->validate([
            'penerima_id' => 'required|exists:pegawai,id',
            'pesan'       => 'required|string|max:255',
        ]);

        Kudos::create([
            'pengirim_id' => auth()->user()->pegawai->id,
            'penerima_id' => $validated['penerima_id'],
            'pesan'       => $validated['pesan'],
        ]);

        return back()->with('success', 'Kudos apresiasi berhasil dikirim!');
    }

    public function updateProfil(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'email'               => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'nama_lengkap'        => 'required|string|max:100',
            'jenis_kelamin'       => 'required|in:Laki-laki,Perempuan',
            'umur'                => 'required|integer|min:18|max:65',
            'pendidikan_terakhir' => 'required|string|max:50',
            'alamat'              => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($user, $validated) {
            $user->update([
                'email' => $validated['email'],
            ]);

            $user->pegawai->update([
                'nama_lengkap'        => $validated['nama_lengkap'],
                'jenis_kelamin'       => $validated['jenis_kelamin'],
                'umur'                => $validated['umur'],
                'pendidikan_terakhir' => $validated['pendidikan_terakhir'],
                'alamat'              => $validated['alamat'],
            ]);
        });

        return back()->with('success', 'Profil dan email akun berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password'         => 'required|min:8|confirmed',
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }
}