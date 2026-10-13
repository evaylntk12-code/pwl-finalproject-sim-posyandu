<?php

namespace App\Http\Controllers;

use App\Support\DummyData;

class JadwalController extends Controller
{
    public function index()
    {
        // Daftar hanya berisi yang masih menunggu. Yang Selesai pindah ke Laporan.
        $jadwal = array_filter(DummyData::jadwal(), fn ($j) => $j['status'] === 'Terjadwal');
        $imunisasi = array_filter(DummyData::imunisasi(), fn ($i) => $i['status'] === 'Terjadwal');

        return view('kader.jadwal.index', compact('jadwal', 'imunisasi'));
    }

    public function createJadwal()
    {
        return view('kader.jadwal.form-jadwal', ['jadwal' => null]);
    }

    public function editJadwal(int $id)
    {
        $jadwal = DummyData::cari(DummyData::jadwal(), $id);
        abort_if(is_null($jadwal), 404);
        abort_if($jadwal['status'] === 'Selesai', 403, 'Data yang sudah selesai terkunci.');

        return view('kader.jadwal.form-jadwal', compact('jadwal'));
    }

    public function createImunisasi()
    {
        return view('kader.jadwal.form-imunisasi', [
            'imunisasi' => null,
            'daftarBalita' => DummyData::balita(),
        ]);
    }

    public function editImunisasi(int $id)
    {
        $imunisasi = DummyData::cari(DummyData::imunisasi(), $id);
        abort_if(is_null($imunisasi), 404);
        abort_if($imunisasi['status'] === 'Selesai', 403, 'Data yang sudah selesai terkunci.');

        return view('kader.jadwal.form-imunisasi', [
            'imunisasi' => $imunisasi,
            'daftarBalita' => DummyData::balita(),
        ]);
    }
}
