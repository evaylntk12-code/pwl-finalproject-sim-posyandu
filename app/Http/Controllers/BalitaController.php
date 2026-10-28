<?php

namespace App\Http\Controllers;

use App\Models\Toddler;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class BalitaController extends Controller
{
        // READ: daftar balita dengan pencarian, filter, pengurutan, dan pagination
    public function index(Request $request)
    {
        // Pilihan urutan (whitelist). Kunci = nilai parameter ?urut=, isi = [kolom, arah, label].
        // Kolom tidak diambil langsung dari request, jadi tidak bisa disisipi nama kolom sembarangan.
        $opsiUrut = [
            'nama_asc'   => ['name', 'asc', 'Nama A-Z'],
            'nama_desc'  => ['name', 'desc', 'Nama Z-A'],
            'lahir_desc' => ['birth_date', 'desc', 'Termuda dulu'],
            'lahir_asc'  => ['birth_date', 'asc', 'Tertua dulu'],
            'terbaru'    => ['created_at', 'desc', 'Terakhir ditambahkan'],
        ];

        $q      = trim((string) $request->query('q', ''));
        $gender = (string) $request->query('gender', '');
        $urut   = (string) $request->query('urut', 'nama_asc');

        if (! isset($opsiUrut[$urut])) {
            $urut = 'nama_asc';
        }
        [$kolom, $arah] = $opsiUrut[$urut];

        // Query dibangun bertahap; kondisi baru ditambahkan jika inputnya terisi
        $balita = Toddler::with('user') // eager loading: data wali diambil sekaligus
            ->when($q !== '', function ($query) use ($q) {
                // Kelompokkan kondisi OR dalam satu closure agar tidak bentrok dengan filter lain
                $query->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%{$q}%")
                        ->orWhereHas('user', function ($u) use ($q) {
                            $u->where('name', 'like', "%{$q}%")
                                ->orWhere('email', 'like', "%{$q}%");
                        });
                });
            })
            ->when(in_array($gender, ['L', 'P'], true), fn ($query) => $query->where('gender', $gender))
            ->orderBy($kolom, $arah)
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString(); // search, filter, dan urutan tetap terbawa saat pindah halaman

        return view('kader.balita.index', compact('balita', 'opsiUrut', 'urut', 'q', 'gender'));
    }


    // CREATE: hanya menampilkan form
    public function create()
    {
        return view('kader.balita.form', ['balita' => null]);
    }

    // STORE: validasi, lalu simpan akun orang tua dan balita dalam satu transaksi
    public function store(Request $request)
    {
        $validated = $request->validate($this->aturan(), $this->pesan(), $this->atribut());

        DB::transaction(function () use ($validated) {
            $akun = User::create([
                'name'                 => $validated['guardian_name'],
                'email'                => $validated['email'],
                'password'             => Hash::make($validated['password']), // disimpan dalam bentuk hash
                'role'                 => 'orang_tua',
                'phone'                => $validated['phone'] ?? null,
                'address'              => $validated['address'] ?? null,
                'must_change_password' => true, // wajib ganti kata sandi saat masuk pertama
            ]);

            Toddler::create([
                'user_id'    => $akun->id,
                'name'       => $validated['name'],
                'birth_date' => $validated['birth_date'],
                'gender'     => $validated['gender'],
            ]);
        });

        return redirect()
            ->route('kader.balita.index')
            ->with('success', 'Data balita dan akun orang tua berhasil ditambahkan.');
    }

    // EDIT: menampilkan form berisi data lama (route model binding)
    public function edit(Toddler $balita)
    {
        $balita->load('user');

        return view('kader.balita.form', compact('balita'));
    }

    // UPDATE: validasi, lalu ubah data balita dan data wali (kata sandi tidak diubah di sini)
    public function update(Request $request, Toddler $balita)
    {
        $validated = $request->validate($this->aturan($balita), $this->pesan(), $this->atribut());

        DB::transaction(function () use ($validated, $balita) {
            $balita->update([
                'name'       => $validated['name'],
                'birth_date' => $validated['birth_date'],
                'gender'     => $validated['gender'],
            ]);

            $balita->user->update([
                'name'    => $validated['guardian_name'],
                'email'   => $validated['email'],
                'phone'   => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
            ]);
        });

        return redirect()
            ->route('kader.balita.index')
            ->with('success', 'Data balita dan wali berhasil diperbarui.');
    }

    // DELETE: hapus balita, lalu akun orang tuanya
    public function destroy(Toddler $balita)
    {
        DB::transaction(function () use ($balita) {
            $akun = $balita->user;

            // Pemeriksaan dan imunisasi ikut terhapus otomatis (CASCADE di database)
            $balita->delete();

            // Akun dihapus setelah balita (aturan RESTRICT). Akun kader tidak pernah dihapus lewat sini.
            if ($akun->role === 'orang_tua') {
                $akun->delete();
            }
        });

        return redirect()
            ->route('kader.balita.index')
            ->with('success', 'Data balita dan akun orang tuanya berhasil dihapus.');
    }

    // RESET SANDI: buat kata sandi sementara, tampilkan sekali lewat flash message
    public function resetSandi(Toddler $balita)
    {
        $sementara = $this->sandiSementara();

        $balita->user->update([
            'password'             => Hash::make($sementara),
            'must_change_password' => true,
        ]);

        return redirect()
            ->route('kader.balita.index')
            ->with('success', 'Kata sandi berhasil direset.')
            ->with('sandi_sementara', $sementara)
            ->with('sandi_untuk', $balita->user->name);
    }

    // ---------- Bagian pendukung ----------

    // 8 karakter acak, tanpa karakter yang mudah tertukar (0/O, 1/I)
    private function sandiSementara(): string
    {
        $huruf = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';

        return collect(range(1, 8))
            ->map(fn () => $huruf[random_int(0, strlen($huruf) - 1)])
            ->implode('');
    }

    private function aturan(?Toddler $balita = null): array
    {
        $aturan = [
            'name'          => ['required', 'string', 'max:100'],
            'birth_date'    => [
                'required',
                'date',
                'before_or_equal:today',
                'after_or_equal:' . now()->subYears(5)->toDateString(), // balita maksimal 5 tahun
            ],
            'gender'        => ['required', Rule::in(['L', 'P'])],
            'guardian_name' => ['required', 'string', 'max:255'],
            'phone'         => ['nullable', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'address'       => ['nullable', 'string', 'max:500'],
            // ignore(): saat ubah data, email milik akun yang sedang diedit tidak dihitung duplikat
            'email'         => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($balita?->user_id),
            ],
        ];

        // Kata sandi awal hanya diisi saat menambah balita baru
        if (is_null($balita)) {
            $aturan['password'] = ['required', 'string', 'min:8'];
        }

        return $aturan;
    }

    private function pesan(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string'   => ':attribute harus berupa teks.',
            'max'      => ':attribute maksimal :max karakter.',
            'min'      => ':attribute minimal :min karakter.',
            'date'     => ':attribute harus berupa tanggal yang valid.',
            'in'       => ':attribute tidak valid.',
            'email'    => ':attribute harus berupa alamat email yang valid.',
            'birth_date.before_or_equal' => 'Tanggal lahir tidak boleh lebih dari hari ini.',
            'birth_date.after_or_equal'  => 'Balita berusia maksimal 5 tahun.',
            'phone.regex'                => 'No. HP hanya boleh berisi angka, spasi, tanda + dan - (8 sampai 20 karakter).',
            'email.unique'               => 'Email ini sudah terdaftar.',
        ];
    }

    private function atribut(): array
    {
        return [
            'name'          => 'Nama balita',
            'birth_date'    => 'Tanggal lahir',
            'gender'        => 'Jenis kelamin',
            'guardian_name' => 'Nama wali',
            'phone'         => 'No. HP',
            'address'       => 'Alamat',
            'email'         => 'Email',
            'password'      => 'Kata sandi awal',
        ];
    }
}