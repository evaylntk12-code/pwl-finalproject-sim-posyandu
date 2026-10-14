<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        Schedule::create([
            'activity_date' => '2026-10-11',
            'start_time'    => '09:00',
            'end_time'      => '12:00',
            'description'   => 'Penimbangan dan imunisasi',
            'status'        => 'Terjadwal',
        ]);

        Schedule::create([
            'activity_date' => '2026-09-13',
            'start_time'    => '09:00',
            'end_time'      => '12:00',
            'description'   => 'Penimbangan rutin',
            'status'        => 'Selesai',
        ]);
    }
}