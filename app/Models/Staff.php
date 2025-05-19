<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staffs';

    protected $fillable = [
        'user_id',
       
        'first_name',
        'last_name',
        'middle_name',
        'supervisor_id',
        'profile_image',
        'address',
        'department',
        'hire_date',
    ];

    protected $casts = [
        'hire_date' => 'date',
    ];

    protected $hidden = [
        'user_id',
    ];


    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }



    public function supervisor()
    {
        return $this->belongsTo(Staff::class, 'supervisor_id');
    }

    public function subordinates()
    {
        return $this->hasMany(Staff::class, 'supervisor_id');
    }

    public function roles()
    {
        return $this->belongsToMany(StaffRole::class, 'role_staffs', 'staff_id', 'role_id')
                    ->withTimestamps();
    }
}
