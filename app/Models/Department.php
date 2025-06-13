<?php

// app/Models/Department.php

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
        'name', // Only 'name' is fillable for now
    ];

    /**
     * Get the users (instructors/students) associated with the department.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the courses belonging to this department.
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}