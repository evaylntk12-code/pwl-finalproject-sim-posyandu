<?php

namespace Database\Seeders;

use App\Models\Immunization;
use App\Models\Toddler;
use App\Models\User;
use Illuminate\Database\Seeder;

class ImmunizationSeeder extends Seeder
{
    public function run(): void
    {
        // Data contoh dianggap dicatat oleh bidan
        $bidan = User::where('role', User::ROLE_BIDAN)->first();

        // [balita, jenis, tanggal, berikutnya, status]
        $data = [
            ['Nama Balita 1', 'Campak-Rubela (MR)', '2026-10-11', '2026-11-11', 'Terjadwal'],
            ['Nama Balita 2', 'DPT-HB-Hib', '2026-09-13', '2026-10-11', 'Selesai'],
        ];

        foreach ($data as [$balita, $jenis, $tanggal, $berikutnya, $status]) {
            $anak = Toddler::where('name', $balita)->firstOrFail();

            Immunization::create([
                'toddler_id'        => $anak->id,
                'user_id'           => $bidan?->id,
                'vaccine_type'      => $jenis,
                'immunization_date' => $tanggal,
                'next_date'         => $berikutnya,
                'status'            => $status,
            ]);
        }
    }
}