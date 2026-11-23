<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // Nama role dipakai konsisten di migration, seeder, route, dan middleware
    public const ROLE_KADER     = 'kader';
    public const ROLE_BIDAN     = 'bidan';
    public const ROLE_ORANG_TUA = 'orang_tua';

    public const ROLES = [self::ROLE_KADER, self::ROLE_BIDAN, self::ROLE_ORANG_TUA];

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'must_change_password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'    => 'datetime',
            'password'             => 'hashed',
            'must_change_password' => 'boolean',
        ];
    }

    // Apakah akun ini memiliki salah satu role yang diberikan
    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    // Awalan nama route area milik role ini: kader.*, bidan.*, ortu.*
    public function areaPrefix(): string
    {
        return match ($this->role) {
            self::ROLE_KADER => 'kader',
            self::ROLE_BIDAN => 'bidan',
            default          => 'ortu',
        };
    }

    // Nama route dashboard sesuai role
    public function dashboardRoute(): string
    {
        return $this->areaPrefix() . '.dashboard';
    }

    // Balita milik akun orang tua ini (satu akun satu balita)
    public function toddler(): HasOne
    {
        return $this->hasOne(Toddler::class);
    }
}