<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\User;
use App\Services\StatistikPegawaiService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PegawaiController extends Controller
{
    use AuthorizesRequests;

    public function dashboardAdmin(StatistikPegawaiService $statistikService)
    {
        $pegawais            = Pegawai::with('user')->latest()->get();
        $totalPegawai        = $pegawais->count();
        $statistikGender     = $statistikService->getStatistikGender();
        $statistikUmur       = $statistikService->getStatistikUmur();
        $statistikPendidikan = $statistikService->getStatistikPendidikan();
        $rataRataUmur        = $statistikService->getRataRataUmur();

        return view('admin.dashboard', compact(
            'pegawais',
            'totalPegawai',
            'statistikGender',
            'statistikUmur',
            'statistikPendidikan',
            'rataRataUmur'
        ));
    }

    public function index()
    {
        $this->authorize('viewAny', Pegawai::class);
        $pegawais = Pegawai::with('user')->latest()->get();

        return view('admin.pegawai.index', compact('pegawais'));
    }

    public function create()
    {
        $this->authorize('create', Pegawai::class);
        $kodeOtomatis = Pegawai::generateKodePegawai();

        return view('admin.pegawai.create', compact('kodeOtomatis'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Pegawai::class);

        $validated = $request->validate([
            'email'               => 'required|email|unique:users,email',
            'nama_lengkap'        => 'required|string|max:100',
            'jenis_kelamin'       => 'required|in:Laki-laki,Perempuan',
            'umur'                => 'required|integer|min:18|max:65',
            'pendidikan_terakhir' => 'required|string|max:50',
            'jabatan'             => 'required|string|max:50',
            'alamat'              => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'email'    => $validated['email'],
                'password' => Hash::make('Pegawai#123'),
                'role'     => 'pegawai',
            ]);

            Pegawai::create([
                'user_id'             => $user->id,
                'kode_pegawai'        => Pegawai::generateKodePegawai(),
                'nama_lengkap'        => $validated['nama_lengkap'],
                'jenis_kelamin'       => $validated['jenis_kelamin'],
                'umur'                => $validated['umur'],
                'pendidikan_terakhir' => $validated['pendidikan_terakhir'],
                'jabatan'             => $validated['jabatan'],
                'alamat'              => $validated['alamat'],
            ]);
        });

        return redirect()
            ->route('pegawai.index')
            ->with('success', 'Pegawai baru berhasil didaftarkan (Password default: Pegawai#123).');
    }

    public function edit(Pegawai $pegawai)
    {
        $this->authorize('update', $pegawai);

        return view('admin.pegawai.edit', compact('pegawai'));
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $this->authorize('update', $pegawai);

        $validated = $request->validate([
            'nama_lengkap'        => 'required|string|max:100',
            'jenis_kelamin'       => 'required|in:Laki-laki,Perempuan',
            'umur'                => 'required|integer|min:18|max:65',
            'pendidikan_terakhir' => 'required|string|max:50',
            'jabatan'             => 'required|string|max:50',
            'alamat'              => 'required|string|max:255',
        ]);

        $pegawai->update($validated);

        return redirect()
            ->route('pegawai.index')
            ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(Pegawai $pegawai)
    {
        $this->authorize('delete', $pegawai);
        $pegawai->user()->delete();

        return redirect()
            ->route('pegawai.index')
            ->with('success', 'Data pegawai berhasil dihapus.');
    }
}