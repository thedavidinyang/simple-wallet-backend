<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoleStaff extends Model
{
    // this is the role asignment pivote table for staffs
    use HasFactory;

    protected $table = 'role_staffs';


    protected $fillable = [
        'role_id',
        'staff_id',
    ];

    // Relationships
    public function role()
    {
        return $this->belongsTo(StaffRole::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
