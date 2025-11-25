<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\PendaftarStatus;

class Pendaftar extends Model
{
    use HasFactory;

    protected $table = 'pendaftar';

    protected $fillable = [
        'user_id',

        'no_pendaftaran',
        'gelombang_id',
        'jurusan_id',
        'status',
        'user_verifikasi_adm',
        'tgl_verifikasi_adm',
        'user_verifikasi_payment',
        'tgl_verifikasi_payment',
        'wilayah_id'
    ];

    protected $casts = [
        'tgl_verifikasi_adm' => 'datetime',
        'tgl_verifikasi_payment' => 'datetime'
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Gelombang
    public function gelombang()
    {
        return $this->belongsTo(Gelombang::class, 'gelombang_id');
    }

    // Relasi ke Jurusan
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }

    // Relasi ke Data Siswa
    public function dataSiswa()
    {
        return $this->hasOne(PendaftarDataSiswa::class, 'pendaftar_id');
    }

    // Relasi ke Data Orang Tua
    public function dataOrtu()
    {
        return $this->hasOne(PendaftarDataOrtu::class, 'pendaftar_id');
    }

    // Relasi ke Data Sekolah Asal
    public function asalSekolah()
    {
        return $this->hasOne(PendaftarAsalSekolah::class, 'pendaftar_id');
    }
    


    // Relasi ke Berkas
    public function berkas()
    {
        return $this->hasMany(PendaftarBerkas::class, 'pendaftar_id');
    }

    // Relasi ke Pembayaran
    public function pembayaran()
    {
        return $this->hasMany(PendaftarPembayaran::class, 'pendaftar_id');
    }

    // Scope untuk status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}