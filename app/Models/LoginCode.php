<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginCode extends Model
{
    protected $fillable = [
        'email', 'company', 'code', 'expires_at'
    ];

    protected $dates = ['expires_at'];
}
