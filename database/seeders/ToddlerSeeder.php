<?php

namespace Database\Seeders;

use App\Models\Toddler;
use App\Models\User;
use Illuminate\Database\Seeder;

class ToddlerSeeder extends Seeder
{
    public function run(): void
    {
        $balita = [
            ['wali1@email.com', 'Nama Balita 1', '2025-03-12', 'P'],
            ['wali2@email.com', 'Nama Balita 2', '2024-08-20', 'L'],
            ['wali3@email.com', 'Nama Balita 3', '2025-01-05', 'P'],
        ];

        foreach ($balita as [$email, $nama, $lahir, $jk]) {
            $akun = User::where('email', $email)->firstOrFail();

            Toddler::create([
                'user_id'    => $akun->id,
                'name'       => $nama,
                'birth_date' => $lahir,
                'gender'     => $jk,
            ]);
        }
    }
}