<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaffRole extends Model
{

    // this are all the roles that can be assigned to a staff
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    protected $hidden =[
        'id',
        'created_at',
        'updated_at',
        'pivot'
    ];

    // Relationships
    public function staff()
    {
        return $this->belongsToMany(Staff::class, 'role_staffs', 'role_id', 'staff_id')
                    ->withTimestamps();
    }


    public function permissions()
    {
        return $this->hasMany(StaffRolePermission::class, 'role_id');
    }
}

