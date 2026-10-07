<?php

namespace App\Http\Controllers;

use App\Support\DummyData;

class DashboardController extends Controller
{
    public function kader()
    {
        $jumlahBalita = count(DummyData::balita());

        $pemeriksaanBulanIni = array_filter(
            DummyData::pemeriksaan(),
            fn ($p) => str_starts_with($p['tanggal'], DummyData::BULAN_BERJALAN)
        );
        $jumlahPemeriksaan = count($pemeriksaanBulanIni);
        $jumlahGiziBaik = count(array_filter($pemeriksaanBulanIni, fn ($p) => $p['status_gizi'] === 'Gizi Baik'));

        $jadwal = $this->jadwalTerdekat();

        return view('kader.dashboard', compact('jumlahBalita', 'jumlahPemeriksaan', 'jumlahGiziBaik', 'jadwal'));
    }

    public function ortu()
    {
        $anak = DummyData::balita()[0]; // anak milik orang tua yang login (sementara)
        $jadwal = $this->jadwalTerdekat();
        $imunisasi = collect(DummyData::imunisasi())->firstWhere('status', 'Terjadwal');

        return view('ortu.dashboard', compact('anak', 'jadwal', 'imunisasi'));
    }

    private function jadwalTerdekat(): ?array
    {
        return collect(DummyData::jadwal())->firstWhere('status', 'Terjadwal');
    }
}
