<?php

namespace App\Http\Controllers;

use App\Models\Examination;
use App\Models\Toddler;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PemeriksaanController extends Controller
{
    // READ: hanya menampilkan pemeriksaan bulan berjalan
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

    // CREATE: hanya menampilkan form
    public function create()
    {
        return view('kader.pemeriksaan.form', [
            'pemeriksaan'  => null,
            'daftarBalita' => Toddler::orderBy('name')->get(),
        ]);
    }

    // STORE: validasi, lalu simpan
    public function store(Request $request)
    {
        $validated = $request->validate(
            $this->aturan($request),
            $this->pesan(),
            $this->atribut()
        );

        Examination::create($validated);

        return redirect()
            ->route('kader.pemeriksaan.index')
            ->with('success', 'Data pemeriksaan berhasil ditambahkan.');
    }

    // EDIT: menampilkan form berisi data lama (route model binding)
    public function edit(Examination $pemeriksaan)
    {
        $this->pastikanBelumTerkunci($pemeriksaan);

        return view('kader.pemeriksaan.form', [
            'pemeriksaan'  => $pemeriksaan,
            'daftarBalita' => Toddler::orderBy('name')->get(),
        ]);
    }

    // UPDATE: validasi, lalu simpan perubahan
    public function update(Request $request, Examination $pemeriksaan)
    {
        $this->pastikanBelumTerkunci($pemeriksaan);

        $validated = $request->validate(
            $this->aturan($request, $pemeriksaan),
            $this->pesan(),
            $this->atribut()
        );

        $pemeriksaan->update($validated);

        return redirect()
            ->route('kader.pemeriksaan.index')
            ->with('success', 'Data pemeriksaan berhasil diperbarui.');
    }

    // DELETE
    public function destroy(Examination $pemeriksaan)
    {
        $this->pastikanBelumTerkunci($pemeriksaan);

        $pemeriksaan->delete();

        return redirect()
            ->route('kader.pemeriksaan.index')
            ->with('success', 'Data pemeriksaan berhasil dihapus.');
    }

    // ---------- Bagian pendukung (dipakai bersama oleh store dan update) ----------

    // Pemeriksaan bulan sebelumnya otomatis terkunci
    private function pastikanBelumTerkunci(Examination $pemeriksaan): void
    {
        abort_if(
            ! $pemeriksaan->examination_date->isSameMonth(now(), true),
            403,
            'Data bulan sebelumnya sudah terkunci.'
        );
    }

    private function aturan(Request $request, ?Examination $pemeriksaan = null): array
    {
        return [
            'toddler_id' => ['required', 'exists:toddlers,id'],
            'examination_date' => [
                'required',
                'date',
                'after_or_equal:' . now()->startOfMonth()->toDateString(), // hanya bulan berjalan
                'before_or_equal:today',                                    // tidak boleh tanggal depan
                // Satu balita hanya boleh punya satu pemeriksaan per hari.
                // ignore(): saat update, data yang sedang diedit tidak dihitung sebagai duplikat.
                Rule::unique('examinations', 'examination_date')
                    ->where('toddler_id', $request->input('toddler_id'))
                    ->ignore($pemeriksaan?->id),
            ],
            'weight'             => ['required', 'numeric', 'between:1,40'],
            'height'             => ['required', 'numeric', 'between:30,130'],
            'head_circumference' => ['required', 'numeric', 'between:20,60'],
            'arm_circumference'  => ['required', 'numeric', 'between:5,30'],
            'nutrition_status'   => ['required', Rule::in(['Gizi Baik', 'Gizi Kurang', 'Gizi Lebih'])],
            'notes'              => ['nullable', 'string', 'max:500'],
        ];
    }

    private function pesan(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'numeric'  => ':attribute harus berupa angka.',
            'between'  => ':attribute harus di antara :min dan :max.',
            'date'     => ':attribute harus berupa tanggal yang valid.',
            'exists'   => ':attribute tidak ditemukan.',
            'in'       => ':attribute tidak valid.',
            'string'   => ':attribute harus berupa teks.',
            'max'      => ':attribute maksimal :max karakter.',
            'examination_date.after_or_equal'  => 'Tanggal pemeriksaan hanya bisa di bulan berjalan.',
            'examination_date.before_or_equal' => 'Tanggal pemeriksaan tidak boleh lebih dari hari ini.',
            'examination_date.unique'          => 'Balita ini sudah memiliki pemeriksaan pada tanggal tersebut.',
        ];
    }

    private function atribut(): array
    {
        return [
            'toddler_id'         => 'Balita',
            'examination_date'   => 'Tanggal pemeriksaan',
            'weight'             => 'Berat badan',
            'height'             => 'Tinggi badan',
            'head_circumference' => 'Lingkar kepala',
            'arm_circumference'  => 'Lingkar lengan',
            'nutrition_status'   => 'Status gizi',
            'notes'              => 'Catatan',
        ];
    }
}