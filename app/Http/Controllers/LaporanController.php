<?php

namespace App\Http\Controllers;

use App\Support\DummyData;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $daftarBulan = ['2026-10' => 'Oktober 2026', '2026-09' => 'September 2026'];
        $bulan = $request->query('bulan', DummyData::BULAN_BERJALAN);
        $balitaDipilih = $request->query('balita', 'semua');

        $laporan = array_filter(DummyData::pemeriksaan(), function ($p) use ($bulan, $balitaDipilih) {
            return str_starts_with($p['tanggal'], $bulan)
                && ($balitaDipilih === 'semua' || $p['balita'] === $balitaDipilih);
        });

        return view('kader.laporan.index', [
            'laporan' => $laporan,
            'daftarBulan' => $daftarBulan,
            'daftarBalita' => DummyData::balita(),
            'bulan' => $bulan,
            'balitaDipilih' => $balitaDipilih,
        ]);
    }
}
