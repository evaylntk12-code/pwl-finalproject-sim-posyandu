<?php

namespace App\Http\Controllers;

use App\Support\DummyData;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function kader()
    {
        $jumlahBalita = count(DummyData::balita());

        // Pemeriksaan bulan berjalan
        $pemeriksaanBulanIni = array_filter(
            DummyData::pemeriksaan(),
            fn ($p) => str_starts_with($p['tanggal'], DummyData::BULAN_BERJALAN)
        );
        $jumlahPemeriksaan = count($pemeriksaanBulanIni);
        $jumlahPerluPerhatian = count(array_filter(
            $pemeriksaanBulanIni,
            fn ($p) => in_array($p['status_gizi'], ['Gizi Kurang', 'Gizi Lebih'])
        ));

        $jadwal = $this->jadwalTerdekat();

        // Imunisasi yang masih berstatus Terjadwal
        $imunisasiTerjadwal = array_filter(
            DummyData::imunisasi(),
            fn ($i) => $i['status'] === 'Terjadwal'
        );

        return view('kader.dashboard', compact(
            'jumlahBalita',
            'jumlahPemeriksaan',
            'jumlahPerluPerhatian',
            'jadwal',
            'imunisasiTerjadwal'
        ));
    }

    public function ortu()
    {
        $anak = DummyData::balita()[0]; // anak milik orang tua yang login (sementara)

        // Usia (bulan) dihitung dari tanggal lahir
        $anak['usia'] = (int) Carbon::parse($anak['tgl_lahir'])->diffInMonths(now());

        $jadwal = $this->jadwalTerdekat();

        // Imunisasi anak ini yang masih Terjadwal (yang paling dekat). Kosong kalau tidak ada.
        $imunisasi = collect(DummyData::imunisasi())
            ->where('balita', $anak['nama'])
            ->where('status', 'Terjadwal')
            ->sortBy('tanggal')
            ->first();
        
        // Pemeriksaan terakhir milik anak ini
        $pemeriksaan = collect(DummyData::pemeriksaan())
            ->where('balita', $anak['nama'])
            ->sortByDesc('tanggal')
            ->first();

        return view('ortu.dashboard', compact('anak', 'jadwal', 'imunisasi', 'pemeriksaan'));
    }

    private function jadwalTerdekat(): ?array
    {
        return collect(DummyData::jadwal())->firstWhere('status', 'Terjadwal');
    }
}