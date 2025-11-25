<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regency extends Model
{
    protected $fillable = ['id', 'province_id', 'name'];
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $casts = ['id' => 'string', 'province_id' => 'string'];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function districts()
    {
        return $this->hasMany(District::class);
    }
}