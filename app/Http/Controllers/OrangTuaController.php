<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrangTuaController extends Controller
{
    // Riwayat pemeriksaan anak dari orang tua yang sedang login.
    public function riwayat(Request $request)
    {
        $anak = Auth::user()->toddler;
        abort_if(is_null($anak), 404, 'Akun ini belum terhubung dengan data balita.');

        // Daftar tahun diambil dari data milik anak ini (terbaru di atas)
        $daftarTahun = $anak->examinations()
            ->selectRaw('YEAR(examination_date) as y')
            ->distinct()
            ->orderByDesc('y')
            ->pluck('y')
            ->map(fn ($y) => (string) $y);

        // Validasi pilihan filter, kalau tidak valid tampilkan semua tahun
        $tahun = (string) $request->query('tahun', 'semua');
        if ($tahun !== 'semua' && ! $daftarTahun->contains($tahun)) {
            $tahun = 'semua';
        }

        $riwayat = $anak->examinations()
            ->when($tahun !== 'semua', fn ($q) => $q->whereYear('examination_date', (int) $tahun))
            ->orderByDesc('examination_date')
            ->get();

        return view('ortu.riwayat', compact('anak', 'riwayat', 'daftarTahun', 'tahun'));
    }
}