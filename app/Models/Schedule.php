<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'activity_date',
        'start_time',
        'end_time',
        'description',
        'status',
    ];

    protected function casts(): array
    {
        return ['activity_date' => 'date'];
    }

    // Teks jam, misalnya "09.00 - 12.00" (jam selesai boleh kosong)
    protected function timeRange(): Attribute
    {
        return Attribute::get(function () {
            $mulai = str_replace(':', '.', substr($this->start_time, 0, 5));

            if (! $this->end_time) {
                return $mulai;
            }

            return $mulai . ' - ' . str_replace(':', '.', substr($this->end_time, 0, 5));
        });
    }
}