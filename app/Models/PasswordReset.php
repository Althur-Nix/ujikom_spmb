<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $table = 'password_resets';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'email';
    protected $keyType = 'string';
    protected $fillable = ['email', 'token', 'created_at'];
}
