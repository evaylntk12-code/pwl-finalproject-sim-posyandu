<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Immunization extends Model
{
    // Daftar vaksin standar (imunisasi dasar). Dipakai untuk menu pilihan dan validasi.
    public const JENIS = [
        'Hepatitis B',
        'BCG',
        'Polio tetes (OPV)',
        'Polio suntik (IPV)',
        'DPT-HB-Hib',
        'Campak-Rubela (MR)',
        'PCV',
        'Rotavirus',
    ];
    
    protected $fillable = [
        'toddler_id',
        'user_id',
        'vaccine_type',
        'immunization_date',
        'next_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'immunization_date' => 'date',
            'next_date'         => 'date',
        ];
    }

    public function toddler(): BelongsTo
    {
        return $this->belongsTo(Toddler::class);
    }

    // Akun (kader/bidan) yang mencatat data ini. Bisa kosong bila akunnya sudah dihapus.
    public function pencatat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}