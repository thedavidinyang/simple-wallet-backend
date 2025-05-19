<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    public $fillable = [
        'user_id',
        'balance',
    ];
    public $casts = [
        'balance' => 'decimal:8',
    ];
    public $hidden = [
        'created_at',
        'updated_at',
        'id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
