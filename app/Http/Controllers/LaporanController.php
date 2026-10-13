<?php

namespace App\Http\Controllers;

use App\Support\DummyData;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $daftarBulan = [
            'semua' => 'Semua bulan',
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April',   '05' => 'Mei',      '06' => 'Juni',
            '07' => 'Juli',    '08' => 'Agustus',  '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
        ];
        $daftarTahun = ['2026', '2025', '2024'];

        // Default: bulan dan tahun berjalan
        [$tahunDefault, $bulanDefault] = explode('-', DummyData::BULAN_BERJALAN);

        // Tab aktif: pemeriksaan | imunisasi | kegiatan
        $judulTab = [
            'pemeriksaan' => 'Laporan Pemeriksaan Balita',
            'imunisasi'   => 'Laporan Imunisasi Balita',
            'kegiatan'    => 'Laporan Kegiatan Posyandu',
        ];
        $tab = $request->query('tab', 'pemeriksaan');
        if (! array_key_exists($tab, $judulTab)) {
            $tab = 'pemeriksaan';
        }

        // Validasi pilihan filter, kalau tidak valid pakai default
        $bulan = $request->query('bulan', $bulanDefault);
        if (! array_key_exists($bulan, $daftarBulan)) {
            $bulan = $bulanDefault;
        }
        $tahun = $request->query('tahun', $tahunDefault);
        if (! in_array($tahun, $daftarTahun)) {
            $tahun = $tahunDefault;
        }
        $balitaDipilih = $request->query('balita', 'semua');

        // Kunci periode: "2026-10" untuk satu bulan, "2026" untuk semua bulan
        $kunci = $bulan === 'semua' ? $tahun : $tahun . '-' . $bulan;
        $periode = ($bulan === 'semua' ? 'Semua bulan' : $daftarBulan[$bulan]) . ' ' . $tahun;

        // Pemeriksaan: semua data pada periode terpilih
        $pemeriksaan = array_filter(DummyData::pemeriksaan(), function ($p) use ($kunci, $balitaDipilih) {
            return str_starts_with($p['tanggal'], $kunci)
                && ($balitaDipilih === 'semua' || $p['balita'] === $balitaDipilih);
        });

        // Imunisasi: hanya yang sudah Selesai
        $imunisasi = array_filter(DummyData::imunisasi(), function ($i) use ($kunci, $balitaDipilih) {
            return $i['status'] === 'Selesai'
                && str_starts_with($i['tanggal'], $kunci)
                && ($balitaDipilih === 'semua' || $i['balita'] === $balitaDipilih);
        });

        // Kegiatan posyandu: hanya yang sudah Selesai
        $kegiatan = array_filter(DummyData::jadwal(), function ($j) use ($kunci) {
            return $j['status'] === 'Selesai' && str_starts_with($j['tanggal'], $kunci);
        });

        return view('kader.laporan.index', [
            'tab'           => $tab,
            'judulCetak'    => $judulTab[$tab],
            'periode'       => $periode,
            'daftarBulan'   => $daftarBulan,
            'daftarTahun'   => $daftarTahun,
            'daftarBalita'  => DummyData::balita(),
            'bulan'         => $bulan,
            'tahun'         => $tahun,
            'balitaDipilih' => $balitaDipilih,
            'pemeriksaan'   => $pemeriksaan,
            'imunisasi'     => $imunisasi,
            'kegiatan'      => $kegiatan,
            'jumlah'        => [
                'pemeriksaan' => count($pemeriksaan),
                'imunisasi'   => count($imunisasi),
                'kegiatan'    => count($kegiatan),
            ],
        ]);
    }
}