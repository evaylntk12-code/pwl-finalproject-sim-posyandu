<?php

namespace Tests\Feature;

use App\Models\Examination;
use App\Models\Immunization;
use App\Models\Toddler;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Menguji pembatasan akses (RBAC) lewat URL langsung, bukan lewat menu.
 * Matriks yang diuji sama dengan tabel hak akses di README.md.
 */
class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private int $urut = 0;

    private function akun(string $role): User
    {
        $this->urut++;

        $user = User::create([
            'name'                 => 'Akun ' . $role . ' ' . $this->urut,
            'email'                => $role . $this->urut . '@uji.test',
            'password'             => 'password',
            'role'                 => $role,
            'must_change_password' => false,
        ]);

        if ($role === User::ROLE_ORANG_TUA) {
            $this->balita($user);
        }

        return $user;
    }

    private function balita(User $wali): Toddler
    {
        return Toddler::create([
            'user_id'    => $wali->id,
            'name'       => 'Balita ' . $wali->id,
            'birth_date' => now()->subMonths(12)->toDateString(),
            'gender'     => 'L',
        ]);
    }

    /** Halaman tiap area dan role yang boleh membukanya. */
    public static function matriks(): array
    {
        $k = User::ROLE_KADER;
        $b = User::ROLE_BIDAN;
        $o = User::ROLE_ORANG_TUA;

        return [
            'kader dashboard'    => ['/kader/dashboard',     $k],
            'kader balita'       => ['/kader/balita',        $k],
            'kader balita baru'  => ['/kader/balita/tambah', $k],
            'kader pemeriksaan'  => ['/kader/pemeriksaan',   $k],
            'kader jadwal'       => ['/kader/jadwal',        $k],
            'kader laporan'      => ['/kader/laporan',       $k],
            'bidan dashboard'    => ['/bidan/dashboard',     $b],
            'bidan akun kader'   => ['/bidan/kader',         $b],
            'bidan tambah kader' => ['/bidan/kader/tambah',  $b],
            'bidan balita'       => ['/bidan/balita',        $b],
            'bidan pemeriksaan'  => ['/bidan/pemeriksaan',   $b],
            'bidan jadwal'       => ['/bidan/jadwal',        $b],
            'bidan laporan'      => ['/bidan/laporan',       $b],
            'ortu dashboard'     => ['/orang-tua/dashboard', $o],
            'ortu riwayat'       => ['/orang-tua/riwayat',   $o],
        ];
    }

    /** @dataProvider matriks */
    public function test_tamu_diarahkan_ke_login(string $url): void
    {
        $this->get($url)->assertRedirect('/login');
    }

    /** @dataProvider matriks */
    public function test_hanya_role_yang_sesuai_boleh_membuka_url(string $url, string $diizinkan): void
    {
        foreach (User::ROLES as $role) {
            $respons = $this->actingAs($this->akun($role))->get($url);

            $role === $diizinkan ? $respons->assertOk() : $respons->assertForbidden();
        }
    }

    public function test_perubahan_role_langsung_mengubah_hak_akses(): void
    {
        $user = $this->akun(User::ROLE_KADER);

        $this->actingAs($user)->get('/kader/balita')->assertOk();

        $user->update(['role' => User::ROLE_ORANG_TUA]);

        $this->actingAs($user->fresh())->get('/kader/balita')->assertForbidden();
    }

    public function test_pesan_403_tampil_di_halaman_khusus(): void
    {
        $this->actingAs($this->akun(User::ROLE_KADER))
            ->get('/bidan/kader')
            ->assertForbidden()
            ->assertSee('Akses ditolak');
    }

    public function test_login_mengarah_ke_dashboard_sesuai_role(): void
    {
        $tujuan = [
            User::ROLE_KADER     => '/kader/dashboard',
            User::ROLE_BIDAN     => '/bidan/dashboard',
            User::ROLE_ORANG_TUA => '/orang-tua/dashboard',
        ];

        foreach ($tujuan as $role => $url) {
            $akun = $this->akun($role);

            $this->post('/login', ['email' => $akun->email, 'password' => 'password'])
                ->assertRedirect($url);

            auth()->logout();
        }
    }

    public function test_akun_sandi_sementara_wajib_ganti_sandi(): void
    {
        $bidan = $this->akun(User::ROLE_BIDAN);
        $bidan->update(['must_change_password' => true]);

        $this->actingAs($bidan)->get('/bidan/kader')->assertRedirect('/bidan/ganti-sandi');
        $this->actingAs($bidan)->get('/bidan/ganti-sandi')->assertOk();
    }

    // ---------- Bidan hanya melihat, tidak menulis data lapangan ----------

    public function test_bidan_tidak_bisa_menulis_data_milik_kader(): void
    {
        $bidan = $this->akun(User::ROLE_BIDAN);

        $this->actingAs($bidan)->post('/kader/balita', ['name' => 'X'])->assertForbidden();
        $this->actingAs($bidan)->post('/kader/pemeriksaan', ['toddler_id' => 1])->assertForbidden();
        $this->actingAs($bidan)->post('/kader/jadwal', [])->assertForbidden();

        $this->assertSame(0, Examination::count());
    }

    public function test_halaman_bidan_tidak_menampilkan_tombol_tulis(): void
    {
        $this->actingAs($this->akun(User::ROLE_BIDAN))
            ->get('/bidan/balita')
            ->assertOk()
            ->assertDontSee('+ Tambah Balita');

        $this->actingAs($this->akun(User::ROLE_BIDAN))
            ->get('/bidan/pemeriksaan')
            ->assertOk()
            ->assertDontSee('+ Tambah Pemeriksaan');
    }

    // ---------- Imunisasi: dipegang penuh oleh bidan, kader hanya melihat ----------

    private function imunisasi(Toddler $anak, array $ubah = []): Immunization
    {
        return Immunization::create($ubah + [
            'toddler_id'        => $anak->id,
            'vaccine_type'      => 'BCG',
            'immunization_date' => now()->toDateString(),
            'status'            => 'Terjadwal',
        ]);
    }

    public function test_bidan_menjadwalkan_imunisasi_dan_pencatatnya_tersimpan(): void
    {
        $anak  = $this->akun(User::ROLE_ORANG_TUA)->toddler;
        $bidan = $this->akun(User::ROLE_BIDAN);

        $this->actingAs($bidan)
            ->post('/bidan/imunisasi', ['toddler_id' => $anak->id, 'vaccine_type' => 'DPT-HB-Hib', 'immunization_date' => '2026-12-01'])
            ->assertRedirect('/bidan/jadwal');

        $this->assertSame($bidan->id, Immunization::where('vaccine_type', 'DPT-HB-Hib')->value('user_id'));
        $this->assertSame('Terjadwal', Immunization::where('vaccine_type', 'DPT-HB-Hib')->value('status'));
    }

    public function test_bidan_bisa_mengubah_menghapus_dan_menandai_selesai(): void
    {
        $anak  = $this->akun(User::ROLE_ORANG_TUA)->toddler;
        $bidan = $this->akun(User::ROLE_BIDAN);
        $imun  = $this->imunisasi($anak);

        $this->actingAs($bidan)->get("/bidan/imunisasi/{$imun->id}/ubah")->assertOk();
        $this->actingAs($bidan)
            ->put("/bidan/imunisasi/{$imun->id}", ['toddler_id' => $anak->id, 'vaccine_type' => 'PCV', 'immunization_date' => '2026-12-01'])
            ->assertRedirect('/bidan/jadwal');
        $this->assertSame('PCV', $imun->fresh()->vaccine_type);

        $this->actingAs($bidan)->patch("/bidan/imunisasi/{$imun->id}/selesai")->assertRedirect('/bidan/jadwal');
        $this->assertSame('Selesai', $imun->fresh()->status);

        // Yang sudah selesai terkunci, tidak bisa diubah atau dihapus
        $this->actingAs($bidan)->get("/bidan/imunisasi/{$imun->id}/ubah")->assertForbidden();
        $this->actingAs($bidan)->delete("/bidan/imunisasi/{$imun->id}")->assertForbidden();

        $lain = $this->imunisasi($anak, ['vaccine_type' => 'Rotavirus']);
        $this->actingAs($bidan)->delete("/bidan/imunisasi/{$lain->id}")->assertRedirect('/bidan/jadwal');
        $this->assertNull(Immunization::find($lain->id));
    }

    public function test_kader_dan_orang_tua_tidak_bisa_mengelola_imunisasi(): void
    {
        $anak = $this->akun(User::ROLE_ORANG_TUA)->toddler;
        $imun = $this->imunisasi($anak);
        $data = ['toddler_id' => $anak->id, 'vaccine_type' => 'PCV', 'immunization_date' => '2026-12-01'];

        foreach ([User::ROLE_KADER, User::ROLE_ORANG_TUA] as $role) {
            $u = $this->akun($role);

            // Lewat area bidan: ditolak middleware (403)
            $this->actingAs($u)->get('/bidan/imunisasi/tambah')->assertForbidden();
            $this->actingAs($u)->post('/bidan/imunisasi', $data)->assertForbidden();
            $this->actingAs($u)->get("/bidan/imunisasi/{$imun->id}/ubah")->assertForbidden();
            $this->actingAs($u)->put("/bidan/imunisasi/{$imun->id}", $data)->assertForbidden();
            $this->actingAs($u)->delete("/bidan/imunisasi/{$imun->id}")->assertForbidden();
            $this->actingAs($u)->patch("/bidan/imunisasi/{$imun->id}/selesai")->assertForbidden();
        }

        // Di area kader route tulis imunisasi memang tidak ada (404)
        $kader = $this->akun(User::ROLE_KADER);
        $this->actingAs($kader)->get('/kader/imunisasi/tambah')->assertNotFound();
        $this->actingAs($kader)->post('/kader/imunisasi', $data)->assertNotFound();
        $this->actingAs($kader)->delete("/kader/imunisasi/{$imun->id}")->assertNotFound();

        $this->assertSame(1, Immunization::count());
        $this->assertSame('BCG', $imun->fresh()->vaccine_type);
        $this->assertSame('Terjadwal', $imun->fresh()->status);
    }

    public function test_jenis_imunisasi_harus_dari_daftar_vaksin(): void
    {
        $anak  = $this->akun(User::ROLE_ORANG_TUA)->toddler;
        $bidan = $this->akun(User::ROLE_BIDAN);

        $this->actingAs($bidan)
            ->post('/bidan/imunisasi', ['toddler_id' => $anak->id, 'vaccine_type' => 'campak', 'immunization_date' => '2026-12-01'])
            ->assertSessionHasErrors('vaccine_type');

        $this->assertSame(0, Immunization::count());
    }

    public function test_imunisasi_ganda_ditolak(): void
    {
        $anak = $this->akun(User::ROLE_ORANG_TUA)->toddler;
        $data = ['toddler_id' => $anak->id, 'vaccine_type' => 'BCG', 'immunization_date' => '2026-12-01'];

        $this->actingAs($this->akun(User::ROLE_BIDAN))->post('/bidan/imunisasi', $data)->assertRedirect('/bidan/jadwal');

        // Bidan lain (atau klik ganda) dengan data sama: ditolak
        $this->actingAs($this->akun(User::ROLE_BIDAN))
            ->post('/bidan/imunisasi', $data)
            ->assertSessionHasErrors('immunization_date');

        $this->assertSame(1, Immunization::count());
    }

    // ---------- Pemeriksaan: dua kader tidak boleh menginput data yang sama ----------

    public function test_pemeriksaan_ganda_ditolak_dan_pencatat_tersimpan(): void
    {
        $anak = $this->akun(User::ROLE_ORANG_TUA)->toddler;
        $data = [
            'toddler_id'         => $anak->id,
            'examination_date'   => now()->toDateString(),
            'weight'             => 9.5,
            'height'             => 74,
            'head_circumference' => 45,
            'arm_circumference'  => 14,
            'nutrition_status'   => 'Gizi Baik',
        ];

        $kaderA = $this->akun(User::ROLE_KADER);
        $kaderB = $this->akun(User::ROLE_KADER);

        $this->actingAs($kaderA)->post('/kader/pemeriksaan', $data)->assertRedirect('/kader/pemeriksaan');
        $this->actingAs($kaderB)->post('/kader/pemeriksaan', $data)->assertSessionHasErrors('examination_date');

        $this->assertSame(1, Examination::count());
        $this->assertSame($kaderA->id, Examination::first()->user_id);
    }

    public function test_menghapus_kader_tidak_menghapus_riwayat_pemeriksaan(): void
    {
        $anak  = $this->akun(User::ROLE_ORANG_TUA)->toddler;
        $kader = $this->akun(User::ROLE_KADER);

        Examination::create([
            'toddler_id' => $anak->id, 'user_id' => $kader->id, 'examination_date' => now()->toDateString(),
            'weight' => 9.5, 'height' => 74, 'head_circumference' => 45, 'arm_circumference' => 14,
            'nutrition_status' => 'Gizi Baik',
        ]);

        $this->actingAs($this->akun(User::ROLE_BIDAN))->delete("/bidan/kader/{$kader->id}")->assertRedirect('/bidan/kader');

        $this->assertSame(1, Examination::count());
        $this->assertNull(Examination::first()->user_id);
    }

    // ---------- Kelola akun kader: khusus bidan ----------

    public function test_bidan_menambah_kader_role_selalu_kader(): void
    {
        $this->actingAs($this->akun(User::ROLE_BIDAN))
            ->post('/bidan/kader', [
                'name'     => 'Kader Baru',
                'email'    => 'baru@uji.test',
                'password' => 'rahasia123',
                'role'     => User::ROLE_BIDAN, // upaya menyisipkan role lewat form harus diabaikan
            ])
            ->assertRedirect('/bidan/kader');

        $baru = User::where('email', 'baru@uji.test')->firstOrFail();
        $this->assertSame(User::ROLE_KADER, $baru->role);
        $this->assertTrue($baru->must_change_password);
    }

    public function test_kader_tidak_bisa_membuat_akun_apa_pun(): void
    {
        $this->actingAs($this->akun(User::ROLE_KADER))
            ->post('/bidan/kader', ['name' => 'X', 'email' => 'x@uji.test', 'password' => 'rahasia123', 'role' => 'bidan'])
            ->assertForbidden();

        $this->assertDatabaseMissing('users', ['email' => 'x@uji.test']);
    }

    public function test_bidan_tidak_bisa_mengubah_atau_menghapus_akun_non_kader(): void
    {
        $bidanLain = $this->akun(User::ROLE_BIDAN);
        $ortu      = $this->akun(User::ROLE_ORANG_TUA);
        $bidan     = $this->akun(User::ROLE_BIDAN);

        foreach ([$bidanLain, $ortu, $bidan] as $target) {
            $this->actingAs($bidan)->get("/bidan/kader/{$target->id}/ubah")->assertNotFound();
            $this->actingAs($bidan)->delete("/bidan/kader/{$target->id}")->assertNotFound();
            $this->assertDatabaseHas('users', ['id' => $target->id]);
        }
    }

    public function test_bidan_mereset_sandi_kader(): void
    {
        $kader = $this->akun(User::ROLE_KADER);
        $lama  = $kader->password;

        $this->actingAs($this->akun(User::ROLE_BIDAN))
            ->put("/bidan/kader/{$kader->id}/reset-sandi")
            ->assertRedirect('/bidan/kader')
            ->assertSessionHas('sandi_sementara');

        $kader->refresh();
        $this->assertNotSame($lama, $kader->password);
        $this->assertTrue($kader->must_change_password);
    }

    // ---------- Tampilan dengan data seeder asli ----------

    public function test_tampilan_tiap_role_dengan_data_seeder(): void
    {
        $this->seed();

        $kader = User::where('email', 'kader@email.com')->firstOrFail();
        $bidan = User::where('email', 'bidan@email.com')->firstOrFail();

        // Kader: bisa mengelola jadwal kegiatan, imunisasi hanya tampil (tanpa tombol apa pun)
        $this->actingAs($kader)->get('/kader/jadwal')
            ->assertOk()
            ->assertSee('+ Tambah Jadwal')
            ->assertSee('Nama Balita 1')
            ->assertDontSee('+ Jadwalkan Imunisasi')
            ->assertDontSee('selesai-imunisasi-')
            ->assertDontSee('Ubah imunisasi')
            ->assertDontSee('Hapus imunisasi');

        $this->actingAs($kader)->get('/kader/balita')->assertOk()->assertSee('+ Tambah Balita');
        $this->actingAs($kader)->get('/kader/pemeriksaan')->assertOk()->assertSee('+ Tambah Pemeriksaan');

        // Bidan: mengelola imunisasi, tetapi jadwal kegiatan hanya dilihat
        $this->actingAs($bidan)->get('/bidan/jadwal')
            ->assertOk()
            ->assertSee('+ Jadwalkan Imunisasi')
            ->assertSee('selesai-imunisasi-')
            ->assertSee('Ubah imunisasi')
            ->assertDontSee('+ Tambah Jadwal');

        $this->actingAs($bidan)->get('/bidan/balita')->assertOk()->assertDontSee('+ Tambah Balita');
        $this->actingAs($bidan)->get('/bidan/pemeriksaan')->assertOk()->assertDontSee('+ Tambah Pemeriksaan');
        $this->actingAs($bidan)->get('/bidan/dashboard')->assertOk();
        $this->actingAs($bidan)->get('/bidan/kader')->assertOk()->assertSee('kader@email.com');
        $this->actingAs($bidan)->get('/bidan/laporan')->assertOk();
        $this->actingAs($kader)->get('/kader/laporan')->assertOk();

        // Form imunisasi hanya untuk bidan, berisi menu vaksin standar
        $this->actingAs($bidan)->get('/bidan/imunisasi/tambah')->assertOk()->assertSee('Jadwalkan Imunisasi')->assertSee('DPT-HB-Hib');
    }
}