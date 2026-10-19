<?php

use App\Http\Controllers\AkunController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BalitaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImunisasiController;
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
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ---------- Area Kader Posyandu ----------
Route::middleware(['auth', 'role:kader', 'sandi.diganti'])->prefix('kader')->name('kader.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'kader'])->name('dashboard');

    // Data Balita & Wali
    Route::resource('balita', BalitaController::class)
        ->except('show')
        ->parameters(['balita' => 'balita']);
    Route::put('balita/{balita}/reset-sandi', [BalitaController::class, 'resetSandi'])->name('balita.reset-sandi');

    // Pemeriksaan
    Route::resource('pemeriksaan', PemeriksaanController::class)->except('show');

    // Jadwal Kegiatan dan Imunisasi
    Route::resource('jadwal', JadwalController::class)->except('show');
    Route::patch('jadwal/{jadwal}/selesai', [JadwalController::class, 'selesai'])->name('jadwal.selesai');

    Route::resource('imunisasi', ImunisasiController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy'])
        ->parameters(['imunisasi' => 'imunisasi']);
    Route::patch('imunisasi/{imunisasi}/selesai', [ImunisasiController::class, 'selesai'])->name('imunisasi.selesai');

    // Laporan dan akun
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/ganti-sandi', [AkunController::class, 'sandi'])->name('sandi');
    Route::put('/ganti-sandi', [AkunController::class, 'perbarui'])->name('sandi.perbarui');
});

// ---------- Area Orang Tua ----------
Route::middleware(['auth', 'role:orang_tua', 'sandi.diganti'])->prefix('orang-tua')->name('ortu.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'ortu'])->name('dashboard');
    Route::get('/riwayat', [OrangTuaController::class, 'riwayat'])->name('riwayat');
    Route::get('/ganti-sandi', [AkunController::class, 'sandi'])->name('sandi');
    Route::put('/ganti-sandi', [AkunController::class, 'perbarui'])->name('sandi.perbarui');
});