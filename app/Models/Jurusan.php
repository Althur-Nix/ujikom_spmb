<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    use HasFactory;

    protected $table = 'jurusan';

    protected $fillable = [
        'kode',
        'nama',
        'kuota'
    ];

    protected $casts = [
        'kuota' => 'integer'
    ];

    // Relasi ke Pendaftar
    public function pendaftar()
    {
        return $this->hasMany(Pendaftar::class, 'jurusan_id');
    }

    // Relasi ke Pendaftar yang diterima
    public function pendaftarDiterima()
    {
        return $this->hasMany(Pendaftar::class, 'jurusan_id')->where('status', 'ACCEPTED');
    }

    // Scope untuk jurusan aktif
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }
}