<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun bidan = akun puncak. Hanya dibuat lewat seeder, karena tidak ada
        // role di atasnya yang bisa membuatnya. Kata sandi demo: "password"
        User::create([
            'name'                 => 'Ajeng',
            'email'                => 'bidan@email.com',
            'password'             => Hash::make('password'),
            'role'                 => User::ROLE_BIDAN,
            'must_change_password' => false,
        ]);

        // Akun kader demo. Di pemakaian nyata, akun kader dibuat oleh bidan lewat menu Akun Kader.
        // Kata sandi demo: "password"
        User::create([
            'name'                 => 'Rukayyah',
            'email'                => 'kader@email.com',
            'password'             => Hash::make('password'),
            'role'                 => User::ROLE_KADER,
            'must_change_password' => false,
        ]);

        // Akun orang tua demo (wali dari tiap balita)
        $wali = [
            ['Nama Wali 1', 'wali1@email.com', '0812-3456-7890', 'Alamat Wali 1'],
            ['Nama Wali 2', 'wali2@email.com', '0813-1111-2222', 'Alamat Wali 2'],
            ['Nama Wali 3', 'wali3@email.com', '0857-3333-4444', 'Alamat Wali 3'],
        ];

        foreach ($wali as [$nama, $email, $hp, $alamat]) {
            User::create([
                'name'                 => $nama,
                'email'                => $email,
                'password'             => Hash::make('password'),
                'role'                 => User::ROLE_ORANG_TUA,
                'phone'                => $hp,
                'address'              => $alamat,
                'must_change_password' => false,
            ]);
        }
    }
}