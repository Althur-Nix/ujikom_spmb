<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = ['id', 'regency_id', 'name'];
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $casts = ['id' => 'string', 'regency_id' => 'string'];

    public function regency()
    {
        return $this->belongsTo(Regency::class);
    }

    public function villages()
    {
        return $this->hasMany(Village::class);
    }
}