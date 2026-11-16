<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        $kodeOtomatis = Pegawai::generateKodePegawai();
        $initialMode  = 'login';

        return view('auth.login', compact('kodeOtomatis', 'initialMode'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return Auth::user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('pegawai.dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi tidak sesuai.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        $kodeOtomatis = Pegawai::generateKodePegawai();
        $initialMode  = 'register';

        return view('auth.register', compact('kodeOtomatis', 'initialMode'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'email'               => 'required|email|unique:users,email',
            'password'            => 'required|min:8|confirmed',
            'nama_lengkap'        => 'required|string|max:100',
            'jenis_kelamin'       => 'required|in:Laki-laki,Perempuan',
            'umur'                => 'required|integer|min:18|max:65',
            'pendidikan_terakhir' => 'required|string|max:50',
            'alamat'              => 'required|string|max:255',
        ], [], [
            'email' => 'email registrasi',
        ]);

        $user = DB::transaction(function () use ($validated) {
            $newUser = User::create([
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role'     => 'pegawai',
            ]);

            Pegawai::create([
                'user_id'             => $newUser->id,
                'kode_pegawai'        => Pegawai::generateKodePegawai(),
                'nama_lengkap'        => $validated['nama_lengkap'],
                'jenis_kelamin'       => $validated['jenis_kelamin'],
                'umur'                => $validated['umur'],
                'pendidikan_terakhir' => $validated['pendidikan_terakhir'],
                'jabatan'             => 'Staff',
                'alamat'              => $validated['alamat'],
            ]);

            return $newUser;
        });

        Auth::login($user);

        return redirect()
            ->route('pegawai.dashboard')
            ->with('success', 'Akun pegawai berhasil dibuat.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}