<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftarDataSiswa extends Model
{
    use HasFactory;

    protected $table = 'pendaftar_data_siswa';

    protected $fillable = [
        'pendaftar_id',
        'nik',
        'nama',
        'nisn',
        'jenis_kelamin',
        'tempat_lahir',
        'tgl_lahir',
        'alamat',
        'asal_sekolah',
        'province_id',
        'regency_id',
        'district_id',
        'village_id'
    ];

    protected $casts = [
        'tgl_lahir' => 'date'
    ];

    // Relasi ke Pendaftar
    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class, 'pendaftar_id');
    }

    // Relasi ke Wilayah
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function regency()
    {
        return $this->belongsTo(Regency::class, 'regency_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function village()
    {
        return $this->belongsTo(Village::class, 'village_id');
    }
}