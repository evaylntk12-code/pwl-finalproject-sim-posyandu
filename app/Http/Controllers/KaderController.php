<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\MembuatSandiSementara;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

/**
 * Kelola akun Kader. Hanya bisa dibuka oleh role bidan (lihat routes/web.php).
 * Bidan adalah akun puncak (dibuat lewat seeder), sedangkan akun kader dibuat oleh bidan.
 */
class KaderController extends Controller
{
    use MembuatSandiSementara;

    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $kader = User::where('role', User::ROLE_KADER)
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->orderBy('name')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('bidan.kader.index', compact('kader', 'q'));
    }

    public function create()
    {
        return view('bidan.kader.form', ['kader' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->aturan(), $this->pesan(), $this->atribut());

        User::create([
            'name'                 => $validated['name'],
            'email'                => $validated['email'],
            'password'             => Hash::make($validated['password']),
            'role'                 => User::ROLE_KADER, // role ditetapkan server, bukan dari input form
            'phone'                => $validated['phone'] ?? null,
            'must_change_password' => true,
        ]);

        return redirect()
            ->route('bidan.kader.index')
            ->with('success', 'Akun kader berhasil ditambahkan.');
    }

    public function edit(User $kader)
    {
        $this->pastikanKader($kader);

        return view('bidan.kader.form', compact('kader'));
    }

    public function update(Request $request, User $kader)
    {
        $this->pastikanKader($kader);

        $validated = $request->validate($this->aturan($kader), $this->pesan(), $this->atribut());

        $kader->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
        ]);

        return redirect()
            ->route('bidan.kader.index')
            ->with('success', 'Data kader berhasil diperbarui.');
    }

    public function destroy(User $kader)
    {
        $this->pastikanKader($kader);
        $kader->delete();

        return redirect()
            ->route('bidan.kader.index')
            ->with('success', 'Akun kader berhasil dihapus.');
    }

    public function resetSandi(User $kader)
    {
        $this->pastikanKader($kader);

        $sementara = $this->sandiSementara();

        $kader->update([
            'password'             => Hash::make($sementara),
            'must_change_password' => true,
        ]);

        return redirect()
            ->route('bidan.kader.index')
            ->with('success', 'Kata sandi berhasil direset.')
            ->with('sandi_sementara', $sementara)
            ->with('sandi_untuk', $kader->name);
    }

    // ---------- Bagian pendukung ----------

    // Route model binding mengambil user mana pun berdasarkan id.
    // Halaman ini hanya untuk akun kader, jadi akun bidan atau orang tua dianggap tidak ada.
    private function pastikanKader(User $user): void
    {
        abort_unless($user->role === User::ROLE_KADER, 404);
    }

    private function aturan(?User $kader = null): array
    {
        $aturan = [
            'name'  => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($kader?->id),
            ],
        ];

        if (is_null($kader)) {
            $aturan['password'] = ['required', 'string', 'min:8'];
        }

        return $aturan;
    }

    private function pesan(): array
    {
        return [
            'required'     => ':attribute wajib diisi.',
            'string'       => ':attribute harus berupa teks.',
            'max'          => ':attribute maksimal :max karakter.',
            'min'          => ':attribute minimal :min karakter.',
            'email'        => ':attribute harus berupa alamat email yang valid.',
            'phone.regex'  => 'No. HP hanya boleh berisi angka, spasi, tanda + dan - (8 sampai 20 karakter).',
            'email.unique' => 'Email ini sudah terdaftar.',
        ];
    }

    private function atribut(): array
    {
        return [
            'name'     => 'Nama kader',
            'phone'    => 'No. HP',
            'email'    => 'Email',
            'password' => 'Kata sandi awal',
        ];
    }
}