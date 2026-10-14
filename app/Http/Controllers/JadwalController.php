<?php

namespace App\Http\Controllers;

use App\Models\Immunization;
use App\Models\Schedule;
use App\Models\Toddler;

class JadwalController extends Controller
{
    public function index()
    {
        // Daftar hanya berisi yang masih menunggu. Yang Selesai pindah ke Laporan.
        $jadwal = Schedule::where('status', 'Terjadwal')
            ->orderBy('activity_date')
            ->get();

        $imunisasi = Immunization::with('toddler')
            ->where('status', 'Terjadwal')
            ->orderBy('immunization_date')
            ->get();

        return view('kader.jadwal.index', compact('jadwal', 'imunisasi'));
    }

    public function createJadwal()
    {
        return view('kader.jadwal.form-jadwal', ['jadwal' => null]);
    }

    public function editJadwal(int $id)
    {
        $jadwal = Schedule::findOrFail($id);
        abort_if($jadwal->status === 'Selesai', 403, 'Data yang sudah selesai terkunci.');

        return view('kader.jadwal.form-jadwal', compact('jadwal'));
    }

    public function createImunisasi()
    {
        return view('kader.jadwal.form-imunisasi', [
            'imunisasi'    => null,
            'daftarBalita' => Toddler::orderBy('name')->get(),
        ]);
    }

    public function editImunisasi(int $id)
    {
        $imunisasi = Immunization::with('toddler')->findOrFail($id);
        abort_if($imunisasi->status === 'Selesai', 403, 'Data yang sudah selesai terkunci.');

        return view('kader.jadwal.form-imunisasi', [
            'imunisasi'    => $imunisasi,
            'daftarBalita' => Toddler::orderBy('name')->get(),
        ]);
    }
}