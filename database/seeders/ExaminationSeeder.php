<?php

namespace Database\Seeders;

use App\Models\Examination;
use App\Models\Toddler;
use Illuminate\Database\Seeder;

class ExaminationSeeder extends Seeder
{
    public function run(): void
    {
        // [balita, tanggal, BB, TB, L. kepala, L. lengan, status gizi, catatan]
        $data = [
            // Oktober 2026 (bulan berjalan)
            ['Nama Balita 1', '2026-10-11', 9.5, 74, 45, 14, 'Gizi Baik', null],
            ['Nama Balita 2', '2026-10-11', 10.2, 78, 46, 14.5, 'Gizi Lebih', null],
            ['Nama Balita 3', '2026-10-11', 7.4, 68, 43, 12.5, 'Gizi Kurang', 'Perlu pantauan'],
            // September 2026
            ['Nama Balita 1', '2026-09-13', 9.2, 73, 45, 14, 'Gizi Baik', null],
            ['Nama Balita 2', '2026-09-13', 9.9, 77, 46, 14.2, 'Gizi Baik', null],
            // 2025 (untuk mencoba filter tahun)
            ['Nama Balita 1', '2025-12-14', 8.4, 69, 44, 13.8, 'Gizi Baik', null],
            ['Nama Balita 1', '2025-11-09', 8.1, 67, 43.5, 13.5, 'Gizi Baik', null],
        ];

        foreach ($data as [$balita, $tanggal, $bb, $tb, $lk, $ll, $gizi, $catatan]) {
            $anak = Toddler::where('name', $balita)->firstOrFail();

            Examination::create([
                'toddler_id'         => $anak->id,
                'examination_date'   => $tanggal,
                'weight'             => $bb,
                'height'             => $tb,
                'head_circumference' => $lk,
                'arm_circumference'  => $ll,
                'nutrition_status'   => $gizi,
                'notes'              => $catatan,
            ]);
        }
    }
}