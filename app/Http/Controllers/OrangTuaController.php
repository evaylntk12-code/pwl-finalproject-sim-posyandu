<?php

namespace App\Http\Controllers;

use App\Support\DummyData;
use Illuminate\Http\Request;

class OrangTuaController extends Controller
{
    // Riwayat pemeriksaan anak dari orang tua yang login (sementara: balita pertama).
    public function riwayat(Request $request)
    {
        $anak = DummyData::balita()[0];

        $semua = collect(DummyData::pemeriksaan())->where('balita', $anak['nama']);

        // Daftar tahun diambil dari data milik anak ini (terbaru di atas)
        $daftarTahun = $semua
            ->map(fn ($p) => substr($p['tanggal'], 0, 4))
            ->unique()
            ->sortDesc()
            ->values();

        // Validasi pilihan filter, kalau tidak valid tampilkan semua tahun
        $tahun = $request->query('tahun', 'semua');
        if ($tahun !== 'semua' && ! $daftarTahun->contains($tahun)) {
            $tahun = 'semua';
        }

        $riwayat = $semua
            ->filter(fn ($p) => $tahun === 'semua' || str_starts_with($p['tanggal'], $tahun))
            ->sortByDesc('tanggal');

        return view('ortu.riwayat', compact('anak', 'riwayat', 'daftarTahun', 'tahun'));
    }
}