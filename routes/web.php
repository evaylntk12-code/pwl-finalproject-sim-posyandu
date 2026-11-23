<?php

use App\Http\Controllers\AkunController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BalitaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImunisasiController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KaderController;
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

/*
| Pembatasan akses (RBAC) - lihat matriks hak akses di README.md
|
| auth          : harus sudah login (belum login -> diarahkan ke halaman login)
| role:...      : role harus termasuk yang diizinkan (kalau tidak -> 403)
| sandi.diganti : akun dengan sandi sementara wajib ganti sandi dulu
|
| Tiga area, masing-masing dengan awalan URL dan nama route sendiri:
|   /kader      -> kader.*   (mengelola data lapangan)
|   /bidan      -> bidan.*   (memantau, mengesahkan imunisasi, mengelola akun kader)
|   /orang-tua  -> ortu.*    (melihat data anak sendiri)
*/

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

    // Jadwal Kegiatan
    Route::resource('jadwal', JadwalController::class)->except('show');
    Route::patch('jadwal/{jadwal}/selesai', [JadwalController::class, 'selesai'])->name('jadwal.selesai');

    // Imunisasi: kader hanya MELIHAT (daftarnya tampil di halaman jadwal). Tidak ada route tulis di sini.

    // Laporan dan akun
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/ganti-sandi', [AkunController::class, 'sandi'])->name('sandi');
    Route::put('/ganti-sandi', [AkunController::class, 'perbarui'])->name('sandi.perbarui');
});

// ---------- Area Bidan ----------
Route::middleware(['auth', 'role:bidan', 'sandi.diganti'])->prefix('bidan')->name('bidan.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'bidan'])->name('dashboard');

    // Kelola akun kader (bidan = akun puncak, kader dibuat oleh bidan)
    Route::resource('kader', KaderController::class)
        ->except('show')
        ->parameters(['kader' => 'kader']);
    Route::put('kader/{kader}/reset-sandi', [KaderController::class, 'resetSandi'])->name('kader.reset-sandi');

    // Hanya lihat: data balita, pemeriksaan, jadwal kegiatan
    Route::get('balita', [BalitaController::class, 'index'])->name('balita.index');
    Route::get('pemeriksaan', [PemeriksaanController::class, 'index'])->name('pemeriksaan.index');
    Route::get('jadwal', [JadwalController::class, 'index'])->name('jadwal.index');

    // Imunisasi: bidan memegang penuh (jenis vaksin, siapa penerimanya, dan kapan = keputusan medis)
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