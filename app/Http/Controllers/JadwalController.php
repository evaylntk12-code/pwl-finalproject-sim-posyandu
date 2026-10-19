<?php

namespace App\Http\Controllers;

use App\Models\Immunization;
use App\Models\Schedule;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    // READ: halaman gabungan (jadwal kegiatan dan imunisasi) yang masih Terjadwal
    public function index()
    {
        // Yang berstatus Selesai pindah ke Laporan
        $jadwal = Schedule::where('status', 'Terjadwal')
            ->orderBy('activity_date')
            ->orderBy('start_time')
            ->get();

        $imunisasi = Immunization::with('toddler')
            ->where('status', 'Terjadwal')
            ->orderBy('immunization_date')
            ->get();

        return view('kader.jadwal.index', compact('jadwal', 'imunisasi'));
    }

    // CREATE: hanya menampilkan form
    public function create()
    {
        return view('kader.jadwal.form-jadwal', ['jadwal' => null]);
    }

    // STORE: validasi, lalu simpan
    public function store(Request $request)
    {
        $validated = $request->validate($this->aturan(), $this->pesan(), $this->atribut());

        Schedule::create($validated); // status otomatis 'Terjadwal' (nilai bawaan di migration)

        return redirect()
            ->route('kader.jadwal.index')
            ->with('success', 'Jadwal kegiatan berhasil ditambahkan.');
    }

    // EDIT: form berisi data lama (route model binding)
    public function edit(Schedule $jadwal)
    {
        $this->pastikanBelumSelesai($jadwal);

        return view('kader.jadwal.form-jadwal', compact('jadwal'));
    }

    // UPDATE
    public function update(Request $request, Schedule $jadwal)
    {
        $this->pastikanBelumSelesai($jadwal);

        $validated = $request->validate($this->aturan(), $this->pesan(), $this->atribut());

        $jadwal->update($validated);

        return redirect()
            ->route('kader.jadwal.index')
            ->with('success', 'Jadwal kegiatan berhasil diperbarui.');
    }

    // DELETE
    public function destroy(Schedule $jadwal)
    {
        $this->pastikanBelumSelesai($jadwal);

        $jadwal->delete();

        return redirect()
            ->route('kader.jadwal.index')
            ->with('success', 'Jadwal kegiatan berhasil dihapus.');
    }

    // SELESAI: ubah status menjadi Selesai (data pindah ke Laporan dan terkunci)
    public function selesai(Schedule $jadwal)
    {
        if ($jadwal->status === 'Selesai') {
            return redirect()->route('kader.jadwal.index')->with('error', 'Jadwal ini sudah selesai.');
        }

        $jadwal->update(['status' => 'Selesai']);

        return redirect()
            ->route('kader.jadwal.index')
            ->with('success', 'Jadwal ditandai selesai dan dipindahkan ke Laporan.');
    }

    // ---------- Bagian pendukung ----------

    private function pastikanBelumSelesai(Schedule $jadwal): void
    {
        abort_if($jadwal->status === 'Selesai', 403, 'Data yang sudah selesai terkunci.');
    }

    private function aturan(): array
    {
        return [
            'activity_date' => ['required', 'date'],
            'start_time'    => ['required', 'date_format:H:i'],
            'end_time'      => ['nullable', 'date_format:H:i', 'after:start_time'],
            'description'   => ['required', 'string', 'max:255'],
        ];
    }

    private function pesan(): array
    {
        return [
            'required'    => ':attribute wajib diisi.',
            'date'        => ':attribute harus berupa tanggal yang valid.',
            'date_format' => ':attribute harus berupa jam yang valid.',
            'string'      => ':attribute harus berupa teks.',
            'max'         => ':attribute maksimal :max karakter.',
            'end_time.after' => 'Jam selesai harus setelah jam mulai.',
        ];
    }

    private function atribut(): array
    {
        return [
            'activity_date' => 'Tanggal',
            'start_time'    => 'Jam mulai',
            'end_time'      => 'Jam selesai',
            'description'   => 'Keterangan',
        ];
    }
}