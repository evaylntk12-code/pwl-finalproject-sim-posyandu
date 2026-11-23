<?php

namespace App\Http\Controllers;

use App\Models\Immunization;
use App\Models\Toddler;
use Closure;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ImunisasiController extends Controller
{
    // Daftar imunisasi tampil di halaman Jadwal & Imunisasi (kader.jadwal.index / bidan.jadwal.index)
    // Seluruh route imunisasi hanya ada di area bidan (routes/web.php), karena jenis vaksin,
    // penerima, dan jadwalnya adalah keputusan medis. Kader hanya melihat daftarnya.

    // CREATE: hanya menampilkan form
    public function create()
    {
        return view('kader.jadwal.form-imunisasi', [
            'imunisasi'    => null,
            'daftarBalita' => Toddler::orderBy('name')->get(),
            'daftarJenis'  => Immunization::JENIS,
        ]);
    }

    // STORE
    public function store(Request $request)
    {
        $validated = $request->validate($this->aturan($request), $this->pesan(), $this->atribut());

        // user_id = bidan yang menjadwalkan (jejak siapa yang menginput)
        try {
            Immunization::create($validated + ['user_id' => $request->user()->id]);
        } catch (UniqueConstraintViolationException) {
            // Dua akun menyimpan data yang sama di detik yang sama: constraint database yang menahan
            return $this->tolakGanda();
        }

        return redirect()
            ->route($this->tujuan())
            ->with('success', 'Jadwal imunisasi berhasil ditambahkan.');
    }

    // EDIT
    public function edit(Immunization $imunisasi)
    {
        $this->pastikanBelumSelesai($imunisasi);

        return view('kader.jadwal.form-imunisasi', [
            'imunisasi'    => $imunisasi,
            'daftarBalita' => Toddler::orderBy('name')->get(),
            'daftarJenis'  => Immunization::JENIS,
        ]);
    }

    // UPDATE
    public function update(Request $request, Immunization $imunisasi)
    {
        $this->pastikanBelumSelesai($imunisasi);

        $validated = $request->validate($this->aturan($request, $imunisasi), $this->pesan(), $this->atribut());

        try {
            $imunisasi->update($validated);
        } catch (UniqueConstraintViolationException) {
            return $this->tolakGanda();
        }

        return redirect()
            ->route($this->tujuan())
            ->with('success', 'Jadwal imunisasi berhasil diperbarui.');
    }

    // DELETE
    public function destroy(Immunization $imunisasi)
    {
        $this->pastikanBelumSelesai($imunisasi);

        $imunisasi->delete();

        return redirect()
            ->route($this->tujuan())
            ->with('success', 'Jadwal imunisasi berhasil dihapus.');
    }

    // SELESAI: tandai imunisasi sudah diberikan. Ubah status menjadi Selesai (data pindah ke Laporan dan terkunci)
    public function selesai(Immunization $imunisasi)
    {
        if ($imunisasi->status === 'Selesai') {
            return redirect()->route($this->tujuan())->with('error', 'Imunisasi ini sudah selesai.');
        }

        $imunisasi->update(['status' => 'Selesai']);

        return redirect()
            ->route($this->tujuan())
            ->with('success', 'Imunisasi ditandai selesai dan dipindahkan ke Laporan.');
    }

    // ---------- Bagian pendukung ----------

    // Setelah aksi, kembali ke halaman Jadwal & Imunisasi milik bidan
    private function tujuan(): string
    {
        return 'bidan.jadwal.index';
    }

    private function tolakGanda()
    {
        return back()->withInput()->withErrors([
            'immunization_date' => 'Balita ini sudah memiliki imunisasi jenis tersebut pada tanggal itu.',
        ]);
    }

    private function pastikanBelumSelesai(Immunization $imunisasi): void
    {
        abort_if($imunisasi->status === 'Selesai', 403, 'Data yang sudah selesai terkunci.');
    }

    private function aturan(Request $request, ?Immunization $imunisasi = null): array
    {
        return [
            'toddler_id'        => ['required', 'exists:toddlers,id'],
            // Hanya jenis vaksin dari daftar standar yang diterima (menu pilihan di form)
            'vaccine_type'      => ['required', Rule::in(Immunization::JENIS)],
            'immunization_date' => [
                'bail',
                'required',
                'date',
                // Balita yang sama tidak boleh punya imunisasi sejenis pada tanggal yang sama.
                // whereDate dipakai agar pembandingan tanggal benar di MySQL.
                // Saat ubah data, baris yang sedang diedit tidak dihitung duplikat.
                function (string $atribut, mixed $nilai, Closure $gagal) use ($request, $imunisasi) {
                    $ganda = Immunization::where('toddler_id', $request->input('toddler_id'))
                        ->where('vaccine_type', $request->input('vaccine_type'))
                        ->whereDate('immunization_date', $nilai)
                        ->when($imunisasi, fn ($q) => $q->where('id', '!=', $imunisasi->id))
                        ->exists();

                    if ($ganda) {
                        $gagal('Balita ini sudah memiliki imunisasi jenis tersebut pada tanggal itu.');
                    }
                },
            ],
            'next_date'         => ['nullable', 'date', 'after:immunization_date'],
        ];
    }

    private function pesan(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'exists'   => ':attribute tidak ditemukan.',
            'in'       => ':attribute tidak valid, pilih dari daftar.',
            'date'     => ':attribute harus berupa tanggal yang valid.',
            'next_date.after'           => 'Jadwal berikutnya harus setelah tanggal imunisasi.',
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