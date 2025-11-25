<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftarAsalSekolah extends Model
{
    use HasFactory;

    protected $table = 'pendaftar_asal_sekolah';
    protected $guarded = [];

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class, 'pendaftar_id');
    }
}