<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Examination extends Model
{
    protected $fillable = [
        'toddler_id',
        'examination_date',
        'weight',
        'height',
        'head_circumference',
        'arm_circumference',
        'nutrition_status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'examination_date'   => 'date',
            'weight'             => 'float',
            'height'             => 'float',
            'head_circumference' => 'float',
            'arm_circumference'  => 'float',
        ];
    }

    public function toddler(): BelongsTo
    {
        return $this->belongsTo(Toddler::class);
    }
}