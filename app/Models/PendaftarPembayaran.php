<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftarPembayaran extends Model
{
    use HasFactory;

    protected $table = 'pendaftar_pembayaran';

    protected $fillable = [
        'pendaftar_id',
        'bank_tujuan',
        'nominal',
        'tanggal_transfer',
        'nama_pengirim',
        'bukti_transfer',
        'status',
        'catatan'
    ];

    protected $casts = [
        'tanggal_transfer' => 'date',
        'nominal' => 'decimal:2'
    ];

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }
}