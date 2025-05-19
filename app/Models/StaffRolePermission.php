<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaffRolePermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_id',
        'permission',
    ];

    protected $hidden =[
        'role_id',
        'created_at',
        'updated_at'
    ];

    // Relationships
    public function role()
    {
        return $this->belongsTo(StaffRole::class);
    }

}
