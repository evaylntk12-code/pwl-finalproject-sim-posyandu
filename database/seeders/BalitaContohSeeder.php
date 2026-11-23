<?php

namespace Database\Seeders;

use App\Models\Toddler;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Data contoh tambahan (27 balita, dengan 3 balita dari ToddlerSeeder jadi total 30)
 * untuk menguji search, filter, sorting, dan pagination.
 * Kata sandi semua akun: password
 */
class BalitaContohSeeder extends Seeder
{
    public function run(): void
    {
        $nama = [
            'Aisyah', 'Bima', 'Citra', 'Dimas', 'Eka', 'Fajar', 'Gita', 'Hana', 'Indra',
            'Jihan', 'Kirana', 'Lintang', 'Maya', 'Naufal', 'Olivia', 'Putra', 'Qory',
            'Raka', 'Salsa', 'Tegar', 'Umar', 'Vina', 'Wahyu', 'Xena', 'Yusuf', 'Zahra', 'Anindya',
        ];

        foreach ($nama as $i => $n) {
            $akun = User::create([
                'name'                 => 'Wali ' . $n,
                'email'                => 'wali' . (10 + $i) . '@email.com',
                'password'             => Hash::make('password'),
                'role'                 => 'orang_tua',
                'phone'                => '08123456' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                'address'              => 'Kudus',
                'must_change_password' => false,
            ]);

            Toddler::create([
                'user_id'    => $akun->id,
                'name'       => 'Balita ' . $n,
                'birth_date' => now()->subMonths(3 + ($i * 2) % 54)->toDateString(), // usia 3 sampai 56 bulan
                'gender'     => $i % 2 === 0 ? 'P' : 'L',
            ]);
        }
    }
}