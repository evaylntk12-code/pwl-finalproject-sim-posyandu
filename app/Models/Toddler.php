<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Toddler extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'birth_date',
        'gender',
    ];

    protected function casts(): array
    {
        return ['birth_date' => 'date'];
    }

    // Usia dalam bulan, dihitung otomatis dari tanggal lahir
    protected function usia(): Attribute
    {
        return Attribute::get(fn () => (int) $this->birth_date->diffInMonths(now()));
    }

    // Akun orang tua pemilik balita
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function examinations(): HasMany
    {
        return $this->hasMany(Examination::class);
    }

    public function immunizations(): HasMany
    {
        return $this->hasMany(Immunization::class);
    }
}