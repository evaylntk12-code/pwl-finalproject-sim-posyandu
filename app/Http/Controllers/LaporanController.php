<?php

namespace App\Http\Controllers;

use App\Models\Examination;
use App\Models\Immunization;
use App\Models\Schedule;
use App\Models\Toddler;
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

        $daftarBalita = Toddler::orderBy('name')->get();

        // Daftar tahun diambil dari data yang ada, ditambah tahun berjalan (terbaru di atas)
        $daftarTahun = collect()
            ->merge(Examination::selectRaw('YEAR(examination_date) as y')->distinct()->pluck('y'))
            ->merge(Immunization::selectRaw('YEAR(immunization_date) as y')->distinct()->pluck('y'))
            ->merge(Schedule::selectRaw('YEAR(activity_date) as y')->distinct()->pluck('y'))
            ->push(now()->year)
            ->map(fn ($y) => (string) $y)
            ->unique()
            ->sortDesc()
            ->values();

        // Validasi pilihan filter, kalau tidak valid pakai bawaan
        $bulan = (string) $request->query('bulan', now()->format('m'));
        if (! array_key_exists($bulan, $daftarBulan)) {
            $bulan = now()->format('m');
        }

        $tahun = (string) $request->query('tahun', (string) now()->year);
        if (! $daftarTahun->contains($tahun)) {
            $tahun = (string) now()->year;
        }

        $balitaDipilih = (string) $request->query('balita', 'semua');
        if ($balitaDipilih !== 'semua' && ! $daftarBalita->contains('id', (int) $balitaDipilih)) {
            $balitaDipilih = 'semua';
        }

        $periode = ($bulan === 'semua' ? 'Semua bulan' : $daftarBulan[$bulan]) . ' ' . $tahun;
        $namaBalitaDipilih = $balitaDipilih === 'semua'
            ? 'Semua balita'
            : $daftarBalita->firstWhere('id', (int) $balitaDipilih)->name;

        // Pemeriksaan: semua data pada periode terpilih
        $pemeriksaan = Examination::with('toddler')
            ->whereYear('examination_date', $tahun)
            ->when($bulan !== 'semua', fn ($q) => $q->whereMonth('examination_date', (int) $bulan))
            ->when($balitaDipilih !== 'semua', fn ($q) => $q->where('toddler_id', (int) $balitaDipilih))
            ->orderBy('examination_date')
            ->orderBy('id')
            ->get();

        // Imunisasi: hanya yang sudah Selesai
        $imunisasi = Immunization::with('toddler')
            ->where('status', 'Selesai')
            ->whereYear('immunization_date', $tahun)
            ->when($bulan !== 'semua', fn ($q) => $q->whereMonth('immunization_date', (int) $bulan))
            ->when($balitaDipilih !== 'semua', fn ($q) => $q->where('toddler_id', (int) $balitaDipilih))
            ->orderBy('immunization_date')
            ->orderBy('id')
            ->get();

        // Kegiatan posyandu: hanya yang sudah Selesai
        $kegiatan = Schedule::where('status', 'Selesai')
            ->whereYear('activity_date', $tahun)
            ->when($bulan !== 'semua', fn ($q) => $q->whereMonth('activity_date', (int) $bulan))
            ->orderBy('activity_date')
            ->orderBy('id')
            ->get();

        return view('kader.laporan.index', [
            'tab'               => $tab,
            'judulCetak'        => $judulTab[$tab],
            'periode'           => $periode,
            'daftarBulan'       => $daftarBulan,
            'daftarTahun'       => $daftarTahun,
            'daftarBalita'      => $daftarBalita,
            'bulan'             => $bulan,
            'tahun'             => $tahun,
            'balitaDipilih'     => $balitaDipilih,
            'namaBalitaDipilih' => $namaBalitaDipilih,
            'pemeriksaan'       => $pemeriksaan,
            'imunisasi'         => $imunisasi,
            'kegiatan'          => $kegiatan,
            'jumlah'            => [
                'pemeriksaan' => $pemeriksaan->count(),
                'imunisasi'   => $imunisasi->count(),
                'kegiatan'    => $kegiatan->count(),
            ],
        ]);
    }
}