<?php

use App\Http\Controllers\AdminOperasionalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PortalPegawaiController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Area Khusus Admin
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [PegawaiController::class, 'dashboardAdmin'])->name('admin.dashboard');
        Route::resource('pegawai', PegawaiController::class)->except(['show']);

        // Modul Operasional Admin
        // Operasional Admin (Absensi, Cuti, Slip Gaji, Klaim Struk)
    Route::get('/admin/absensi', [AdminOperasionalController::class, 'absensi'])->name('admin.absensi');
    Route::get('/admin/cuti', [AdminOperasionalController::class, 'cuti'])->name('admin.cuti');
    Route::patch('/admin/cuti/{cuti}', [AdminOperasionalController::class, 'updateStatusCuti'])->name('admin.cuti.update');

    Route::get('/admin/slip-gaji', [AdminOperasionalController::class, 'slipGaji'])->name('admin.slip-gaji');
    Route::post('/admin/slip-gaji', [AdminOperasionalController::class, 'storeSlipGaji'])->name('admin.slip-gaji.store');
    Route::delete('/admin/slip-gaji/{slipGaji}', [AdminOperasionalController::class, 'destroySlipGaji'])->name('admin.slip-gaji.destroy');

    // Baru: Persetujuan Klaim Struk Pegawai
    Route::get('/admin/struk', [AdminOperasionalController::class, 'struk'])->name('admin.struk');
    Route::patch('/admin/struk/{klaim}', [AdminOperasionalController::class, 'updateStatusStruk'])->name('admin.struk.update');
    });

    // Area Portal Pegawai
    Route::prefix('portal')->group(function () {
        // Beranda
        Route::get('/dashboard', [PortalPegawaiController::class, 'index'])->name('pegawai.dashboard');

        // Halaman Form Terpisah (Diakses dari Grid Menu)
        Route::get('/absensi', [PortalPegawaiController::class, 'halamanAbsensi'])->name('pegawai.absensi.index');
        Route::get('/cuti', [PortalPegawaiController::class, 'halamanCuti'])->name('pegawai.cuti.index');
        Route::get('/tugas', [PortalPegawaiController::class, 'halamanTugas'])->name('pegawai.tugas.index');
        Route::get('/slip-gaji', [PortalPegawaiController::class, 'halamanSlipGaji'])->name('pegawai.slip-gaji.index');
        Route::get('/profil', [PortalPegawaiController::class, 'halamanProfil'])->name('pegawai.profil.index');
        Route::get('/klaim-struk', [PortalPegawaiController::class, 'halamanKlaim'])->name('pegawai.klaim.index');
        Route::get('/kudos', [PortalPegawaiController::class, 'halamanKudos'])->name('pegawai.kudos.index');

        // Aksi Form (POST / PATCH / PUT)
        Route::post('/absensi/checkin', [PortalPegawaiController::class, 'checkIn'])->name('pegawai.checkin');
        Route::post('/absensi/checkout', [PortalPegawaiController::class, 'checkOut'])->name('pegawai.checkout');
        Route::post('/cuti', [PortalPegawaiController::class, 'ajukanCuti'])->name('pegawai.cuti');
        // Tugas Bulanan (Tambah, Update Status, Edit Detail, Hapus)
        Route::post('/tugas', [PortalPegawaiController::class, 'storeTugas'])->name('pegawai.tugas.store');
        Route::patch('/tugas/{tugas}', [PortalPegawaiController::class, 'updateStatusTugas'])->name('pegawai.tugas.update');
        Route::put('/tugas/{tugas}/edit', [PortalPegawaiController::class, 'updateTugas'])->name('pegawai.tugas.edit');
        Route::delete('/tugas/{tugas}', [PortalPegawaiController::class, 'destroyTugas'])->name('pegawai.tugas.destroy');
        Route::post('/klaim-struk', [PortalPegawaiController::class, 'storeKlaim'])->name('pegawai.klaim.store');
        Route::post('/kudos', [PortalPegawaiController::class, 'storeKudos'])->name('pegawai.kudos.store');
        Route::put('/profil', [PortalPegawaiController::class, 'updateProfil'])->name('pegawai.profil.update');
        Route::put('/password', [PortalPegawaiController::class, 'updatePassword'])->name('pegawai.password.update');
    });
});