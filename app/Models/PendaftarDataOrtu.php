<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftarDataOrtu extends Model
{
    use HasFactory;

    protected $table = 'pendaftar_data_ortu';
    protected $guarded = [];

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class, 'pendaftar_id');
    }
}