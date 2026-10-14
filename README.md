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

| Peran | Email |
|---|---|
| Kader | kader@email.com |
| Orang tua | wali1@email.com, wali2@email.com, wali3@email.com |

Catatan: `migrate:fresh` menghapus semua tabel di database, jadi gunakan hanya di database pengembangan.