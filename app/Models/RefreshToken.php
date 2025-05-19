<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefreshToken extends Model
{
    use HasFactory;


    protected $guarded = [];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    protected $hidden = [
        'user_id',
    ];
    
}
