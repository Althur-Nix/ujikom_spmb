<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    protected $fillable = ['id', 'district_id', 'name'];
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $casts = ['id' => 'string', 'district_id' => 'string'];

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}