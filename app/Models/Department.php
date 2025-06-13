<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', // Only 'name' can be mass-assigned for now
    ];

    /**
     * Get the users (instructors/students/admins) who belong to this department.
     * This defines a One-to-Many relationship (one department can have many users).
     */
    public function users()
    {
        return $this->hasMany(User::class); // Laravel will look for 'department_id' on the 'users' table by default.
    }

    /**
     * Get the courses that are offered by this department.
     * This also defines a One-to-Many relationship (one department can offer many courses).
     */
    public function courses()
    {
        return $this->hasMany(Course::class); // Laravel will look for 'department_id' on the 'courses' table by default.
    }
}