<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftarBerkas extends Model
{
    use HasFactory;

    protected $table = 'pendaftar_berkas';
    protected $guarded = [];

    protected $casts = [
        'ukuran_kb' => 'integer',
        'valid' => 'boolean',
        'is_draft' => 'boolean',
        'uploaded_at' => 'datetime',
    ];

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class, 'pendaftar_id');
    }
}
