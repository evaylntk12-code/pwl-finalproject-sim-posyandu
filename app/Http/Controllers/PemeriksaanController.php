<?php

namespace App\Http\Controllers;

use App\Models\Examination;
use App\Models\Toddler;

class PemeriksaanController extends Controller
{
    // Hanya menampilkan pemeriksaan bulan berjalan.
    public function index()
    {
        // with('toddler') mengambil data balita sekaligus (menghindari query berulang per baris)
        $pemeriksaan = Examination::with('toddler')
            ->whereYear('examination_date', now()->year)
            ->whereMonth('examination_date', now()->month)
            ->orderByDesc('examination_date')
            ->orderBy('id')
            ->get();

        $bulanLabel = now()->locale('id')->translatedFormat('F Y'); // contoh: Oktober 2026

        return view('kader.pemeriksaan.index', compact('pemeriksaan', 'bulanLabel'));
    }

    public function create()
    {
        return view('kader.pemeriksaan.form', [
            'pemeriksaan'  => null,
            'daftarBalita' => Toddler::orderBy('name')->get(),
        ]);
    }

    public function edit(int $id)
    {
        $pemeriksaan = Examination::with('toddler')->findOrFail($id);

        // Pemeriksaan bulan sebelumnya otomatis terkunci
        abort_if(
            ! $pemeriksaan->examination_date->isSameMonth(now(), true),
            403,
            'Data bulan sebelumnya sudah terkunci.'
        );

        return view('kader.pemeriksaan.form', [
            'pemeriksaan'  => $pemeriksaan,
            'daftarBalita' => Toddler::orderBy('name')->get(),
        ]);
    }
}