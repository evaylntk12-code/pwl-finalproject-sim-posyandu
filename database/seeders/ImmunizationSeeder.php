<?php

namespace Database\Seeders;

use App\Models\Immunization;
use App\Models\Toddler;
use Illuminate\Database\Seeder;

class ImmunizationSeeder extends Seeder
{
    public function run(): void
    {
        // [balita, jenis, tanggal, berikutnya, status]
        $data = [
            ['Nama Balita 1', 'Campak', '2026-10-11', '2026-11-11', 'Terjadwal'],
            ['Nama Balita 2', 'DPT', '2026-09-13', '2026-10-11', 'Selesai'],
        ];

        foreach ($data as [$balita, $jenis, $tanggal, $berikutnya, $status]) {
            $anak = Toddler::where('name', $balita)->firstOrFail();

            Immunization::create([
                'toddler_id'        => $anak->id,
                'vaccine_type'      => $jenis,
                'immunization_date' => $tanggal,
                'next_date'         => $berikutnya,
                'status'            => $status,
            ]);
        }
    }
}