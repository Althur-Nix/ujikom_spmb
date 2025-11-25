<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gelombang extends Model
{
    use HasFactory;

    protected $table = 'gelombang';

    protected $fillable = [
        'nama',
        'tahun',
        'tgl_mulai',
        'tgl_selesai',
        'biaya_daftar'
    ];

    protected $casts = [
        'tahun' => 'integer',
        'tgl_mulai' => 'date',
        'tgl_selesai' => 'date',
        'biaya_daftar' => 'decimal:2'
    ];

    // Relasi ke Pendaftar
    public function pendaftar()
    {
        return $this->hasMany(Pendaftar::class, 'gelombang_id');
    }

    // Scope untuk gelombang aktif
    public function scopeAktif($query)
    {
        return $query->where('tgl_mulai', '<=', now())
                    ->where('tgl_selesai', '>=', now());
    }
}