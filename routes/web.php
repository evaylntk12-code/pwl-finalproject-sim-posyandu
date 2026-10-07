<?php

use App\Http\Controllers\AkunController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BalitaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\OrangTuaController;
use App\Http\Controllers\PemeriksaanController;
use Illuminate\Support\Facades\Route;

// ---------- Halaman bersama ----------
Route::get('/', fn () => redirect()->route('login'));
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'proses'])->name('login.proses');
Route::get('/lupa-sandi', [AuthController::class, 'lupa'])->name('lupa-sandi');

// ---------- Area Kader Posyandu (admin) ----------
Route::prefix('kader')->name('kader.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'kader'])->name('dashboard');

    // Data Balita & Wali
    Route::get('/balita', [BalitaController::class, 'index'])->name('balita.index');
    Route::get('/balita/tambah', [BalitaController::class, 'create'])->name('balita.create');
    Route::get('/balita/{id}/ubah', [BalitaController::class, 'edit'])->name('balita.edit');

    // Pemeriksaan
    Route::get('/pemeriksaan', [PemeriksaanController::class, 'index'])->name('pemeriksaan.index');
    Route::get('/pemeriksaan/tambah', [PemeriksaanController::class, 'create'])->name('pemeriksaan.create');
    Route::get('/pemeriksaan/{id}/ubah', [PemeriksaanController::class, 'edit'])->name('pemeriksaan.edit');

    // Jadwal & Imunisasi
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::get('/jadwal/tambah', [JadwalController::class, 'createJadwal'])->name('jadwal.create');
    Route::get('/jadwal/{id}/ubah', [JadwalController::class, 'editJadwal'])->name('jadwal.edit');
    Route::get('/jadwal/imunisasi/tambah', [JadwalController::class, 'createImunisasi'])->name('jadwal.imunisasi.create');
    Route::get('/jadwal/imunisasi/{id}/ubah', [JadwalController::class, 'editImunisasi'])->name('jadwal.imunisasi.edit');

    // Laporan & akun
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/ganti-sandi', [AkunController::class, 'sandi'])->name('sandi');
});

// ---------- Area Orang Tua ----------
Route::prefix('orang-tua')->name('ortu.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'ortu'])->name('dashboard');
    Route::get('/riwayat', [OrangTuaController::class, 'riwayat'])->name('riwayat');
    Route::get('/ganti-sandi', [AkunController::class, 'sandi'])->name('sandi');
});
