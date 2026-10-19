<?php

namespace App\Http\Controllers;

use App\Models\Immunization;
use App\Models\Toddler;
use Illuminate\Http\Request;

class ImunisasiController extends Controller
{
    // Daftar imunisasi tampil di halaman Jadwal & Imunisasi (kader.jadwal.index)

    // CREATE: hanya menampilkan form
    public function create()
    {
        return view('kader.jadwal.form-imunisasi', [
            'imunisasi'    => null,
            'daftarBalita' => Toddler::orderBy('name')->get(),
        ]);
    }

    // STORE
    public function store(Request $request)
    {
        $validated = $request->validate($this->aturan(), $this->pesan(), $this->atribut());

        Immunization::create($validated); // status otomatis 'Terjadwal'

        return redirect()
            ->route('kader.jadwal.index')
            ->with('success', 'Catatan imunisasi berhasil ditambahkan.');
    }

    // EDIT
    public function edit(Immunization $imunisasi)
    {
        $this->pastikanBelumSelesai($imunisasi);

        return view('kader.jadwal.form-imunisasi', [
            'imunisasi'    => $imunisasi,
            'daftarBalita' => Toddler::orderBy('name')->get(),
        ]);
    }

    // UPDATE
    public function update(Request $request, Immunization $imunisasi)
    {
        $this->pastikanBelumSelesai($imunisasi);

        $validated = $request->validate($this->aturan(), $this->pesan(), $this->atribut());

        $imunisasi->update($validated);

        return redirect()
            ->route('kader.jadwal.index')
            ->with('success', 'Catatan imunisasi berhasil diperbarui.');
    }

    // DELETE
    public function destroy(Immunization $imunisasi)
    {
        $this->pastikanBelumSelesai($imunisasi);

        $imunisasi->delete();

        return redirect()
            ->route('kader.jadwal.index')
            ->with('success', 'Catatan imunisasi berhasil dihapus.');
    }

    // SELESAI: ubah status menjadi Selesai (data pindah ke Laporan dan terkunci)
    public function selesai(Immunization $imunisasi)
    {
        if ($imunisasi->status === 'Selesai') {
            return redirect()->route('kader.jadwal.index')->with('error', 'Imunisasi ini sudah selesai.');
        }

        $imunisasi->update(['status' => 'Selesai']);

        return redirect()
            ->route('kader.jadwal.index')
            ->with('success', 'Imunisasi ditandai selesai dan dipindahkan ke Laporan.');
    }

    // ---------- Bagian pendukung ----------

    private function pastikanBelumSelesai(Immunization $imunisasi): void
    {
        abort_if($imunisasi->status === 'Selesai', 403, 'Data yang sudah selesai terkunci.');
    }

    private function aturan(): array
    {
        return [
            'toddler_id'        => ['required', 'exists:toddlers,id'],
            'vaccine_type'      => ['required', 'string', 'max:50'],
            'immunization_date' => ['required', 'date'],
            'next_date'         => ['nullable', 'date', 'after:immunization_date'],
        ];
    }

    private function pesan(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'exists'   => ':attribute tidak ditemukan.',
            'string'   => ':attribute harus berupa teks.',
            'max'      => ':attribute maksimal :max karakter.',
            'date'     => ':attribute harus berupa tanggal yang valid.',
            'next_date.after' => 'Jadwal berikutnya harus setelah tanggal imunisasi.',
        ];
    }

    private function atribut(): array
    {
        return [
            'toddler_id'        => 'Balita',
            'vaccine_type'      => 'Jenis imunisasi',
            'immunization_date' => 'Tanggal imunisasi',
            'next_date'         => 'Jadwal imunisasi berikutnya',
        ];
    }
}