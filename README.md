# SIM Posyandu Balita (Kelompok 3)

Sistem Informasi Manajemen Posyandu Balita berbasis Laravel.

## Cara menjalankan

1. Clone repository ini, lalu masuk ke foldernya.
2. Jalankan: `composer install`
3. Salin `.env.example` menjadi `.env`, lalu jalankan: `php artisan key:generate`
4. Nyalakan MySQL di XAMPP, lalu buat database kosong bernama `posyandu_balita` di phpMyAdmin.
5. Jalankan: `php artisan migrate:fresh --seed`
6. Jalankan: `php artisan serve`, lalu buka http://127.0.0.1:8000

## Akun demo (kata sandi: `password`)

| Role | Email | Dashboard setelah login | Hak akses ringkas |
|---|---|---|---|
| Kader | kader@email.com | `/kader/dashboard` | Kelola balita & akun orang tua, input pemeriksaan, jadwal kegiatan, menjadwalkan imunisasi, laporan |
| Bidan | bidan@email.com | `/bidan/dashboard` | Kelola akun kader, menandai imunisasi Selesai, lihat data dan laporan |
| Orang tua | wali1@email.com, wali2@email.com, wali3@email.com | `/orang-tua/dashboard` | Lihat data dan riwayat anak sendiri |

## Matriks hak akses (RBAC)

| Fitur | Kader | Bidan | Orang tua |
|---|:---:|:---:|:---:|
| Login, logout, ganti kata sandi | Ya | Ya | Ya |
| Kelola akun kader (buat, ubah, reset sandi, hapus) | Tidak | **Ya** | Tidak |
| Data balita & akun orang tua (tambah, ubah, hapus, reset sandi) | **Ya** | Lihat saja | Tidak |
| Input pemeriksaan | **Ya** | Lihat saja | Tidak |
| Jadwal kegiatan (tambah, ubah, selesai, hapus) | **Ya** | Lihat saja | Tidak |
| Menjadwalkan imunisasi (tambah, ubah, hapus) | Ya | Ya | Tidak |
| **Menandai imunisasi "Selesai"** | Tidak | **Ya** | Tidak |
| Laporan (hanya baca) | Ya | Ya | Tidak |
| Riwayat dan data anak sendiri | Tidak | Tidak | **Ya** |

### Alasan pembagian role

- **Kader** adalah petugas lapangan yang mengelola data harian: balita, wali, pemeriksaan, dan jadwal.
- **Bidan** adalah tenaga kesehatan sekaligus penanggung jawab Posyandu. Menyatakan imunisasi telah diberikan adalah tindakan medis, jadi hanya bidan yang boleh menandainya Selesai. Bidan juga yang menentukan siapa yang menjadi kader.
- **Orang tua** hanya boleh melihat data anaknya sendiri. Datanya diambil dari `Auth::user()->toddler`, bukan dari parameter URL, sehingga tidak bisa membuka data anak lain.

### Aliran pembuatan akun

Bidan (akun puncak, dibuat lewat seeder) membuat akun kader, lalu kader membuat akun orang tua. Pemberi akses selalu setingkat atau lebih tinggi daripada yang menerima, sehingga kader tidak bisa membuat akun bidan untuk dirinya sendiri.

Respons penolakan: belum login diarahkan ke `/login` (konsep 401); sudah login tetapi role tidak sesuai mendapat halaman **403**.

## Cara kerja authorization

- Role disimpan di kolom `users.role` (`kader`, `bidan`, `orang_tua`). Konstantanya ada di `App\Models\User`.
- `App\Http\Middleware\CekRole` (alias `role`, didaftarkan di `bootstrap/app.php`) menerima satu atau lebih role, contoh `role:bidan` atau `role:kader,bidan`.
- Route dikelompokkan per area di `routes/web.php` dengan `['auth', 'role:...', 'sandi.diganti']`.
- Menu samping berbeda per role, tetapi menu hanya tampilan. Perlindungan sebenarnya ada di middleware sisi server.
- Tombol tulis disembunyikan dari bidan di halaman yang dipakai bersama, dan route tulisnya juga tidak bisa dibuka bidan (403).

## Integritas data

- Pemeriksaan: satu balita hanya boleh punya satu pemeriksaan per hari (validasi dan constraint unik database).
- Imunisasi: satu balita tidak boleh punya imunisasi sejenis pada tanggal yang sama (validasi dan constraint unik database).
- Kolom `user_id` pada pemeriksaan dan imunisasi mencatat akun yang menginput. Bila akun itu dihapus, riwayat tetap ada dan `user_id` menjadi kosong.
