<?php

namespace App\Http\Controllers;

use App\Support\DummyData;

class OrangTuaController extends Controller
{
    // Riwayat pemeriksaan anak dari orang tua yang login (sementara: Nama Balita 1).
    public function riwayat()
    {
        $riwayat = array_filter(
            DummyData::pemeriksaan(),
            fn ($p) => $p['balita'] === 'Nama Balita 1'
        );

        return view('ortu.riwayat', compact('riwayat'));
    }
}
