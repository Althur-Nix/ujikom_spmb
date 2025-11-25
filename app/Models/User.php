<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // gunakan tabel users (sesuaikan jika kamu memang ingin nama tabel 'pengguna')
    protected $table = 'users';

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Jika migrasi menyimpan password di kolom password_hash,
     * pastikan Laravel auth memakai kolom yang benar.
     */
    public function getAuthPassword()
    {
        return $this->password_hash ?? $this->password;
    }

    // One user => one pendaftar (if user created a registration)
    public function pendaftar()
    {
        return $this->hasOne(Pendaftar::class, 'user_id');
    }

    // Relasi ke Log Aktivitas
    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class, 'user_id');
    }

    // Scope untuk role
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    // Check if user has role
    public function hasRole($role)
    {
        return $this->role === $role;
    }
}