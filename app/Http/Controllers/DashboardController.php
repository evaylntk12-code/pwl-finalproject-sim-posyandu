<?php

namespace App\Http\Controllers;

use App\Models\Examination;
use App\Models\Immunization;
use App\Models\Schedule;
use App\Models\Toddler;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function kader()
    {
        $tahun = now()->year;
        $bulan = now()->month;

        $jumlahBalita = Toddler::count();

        // Pemeriksaan bulan berjalan
        $jumlahPemeriksaan = Examination::whereYear('examination_date', $tahun)
            ->whereMonth('examination_date', $bulan)
            ->count();

        // Gizi kurang dan gizi lebih sama-sama perlu tindak lanjut
        $jumlahPerluPerhatian = Examination::whereYear('examination_date', $tahun)
            ->whereMonth('examination_date', $bulan)
            ->whereIn('nutrition_status', ['Gizi Kurang', 'Gizi Lebih'])
            ->count();

        // Jadwal terdekat yang masih Terjadwal (kosong kalau tidak ada)
        $jadwal = Schedule::where('status', 'Terjadwal')
            ->orderBy('activity_date')
            ->first();

        // Imunisasi yang masih Terjadwal, beserta data balitanya
        $imunisasiTerjadwal = Immunization::with('toddler')
            ->where('status', 'Terjadwal')
            ->orderBy('immunization_date')
            ->get();

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
        // Balita milik akun orang tua yang sedang login (satu akun satu balita)
        $anak = Auth::user()->toddler;
        abort_if(is_null($anak), 404, 'Akun ini belum terhubung dengan data balita.');

        // Pemeriksaan terakhir
        $pemeriksaan = $anak->examinations()
            ->orderByDesc('examination_date')
            ->first();

        // Imunisasi anak ini yang masih Terjadwal (paling dekat). Kosong kalau tidak ada.
        $imunisasi = $anak->immunizations()
            ->where('status', 'Terjadwal')
            ->orderBy('immunization_date')
            ->first();

        // Jadwal posyandu terdekat yang masih Terjadwal
        $jadwal = Schedule::where('status', 'Terjadwal')
            ->orderBy('activity_date')
            ->first();

        return view('ortu.dashboard', compact('anak', 'pemeriksaan', 'imunisasi', 'jadwal'));
    }
}