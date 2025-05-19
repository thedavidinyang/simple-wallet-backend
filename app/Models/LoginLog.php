<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = [
        'jwt_token', 'created_at', 'updated_at', 'user_id'
    ];

    protected $fillable = [
        'user_id', "ip_address", "trusted", 'device', "jwt_token", "browser",
        'platform', 'device_name'
    ];
    
}
