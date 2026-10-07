<?php

namespace App\Http\Controllers;

use App\Support\DummyData;

class PemeriksaanController extends Controller
{
    // Hanya menampilkan pemeriksaan bulan berjalan.
    public function index()
    {
        $pemeriksaan = array_filter(
            DummyData::pemeriksaan(),
            fn ($p) => str_starts_with($p['tanggal'], DummyData::BULAN_BERJALAN)
        );

        return view('kader.pemeriksaan.index', compact('pemeriksaan'));
    }

    public function create()
    {
        return view('kader.pemeriksaan.form', [
            'pemeriksaan' => null,
            'daftarBalita' => DummyData::balita(),
        ]);
    }

    public function edit(int $id)
    {
        $pemeriksaan = DummyData::cari(DummyData::pemeriksaan(), $id);
        abort_if(is_null($pemeriksaan), 404);
        // Data bulan sebelumnya otomatis terkunci
        abort_if(! str_starts_with($pemeriksaan['tanggal'], DummyData::BULAN_BERJALAN), 403, 'Data bulan sebelumnya sudah terkunci.');

        return view('kader.pemeriksaan.form', [
            'pemeriksaan' => $pemeriksaan,
            'daftarBalita' => DummyData::balita(),
        ]);
    }
}
