<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Immunization extends Model
{
    protected $fillable = [
        'toddler_id',
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
}