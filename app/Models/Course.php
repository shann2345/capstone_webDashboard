<?php

// app/Models/Course.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'course_code',
        'description',
        'credits',
        'department_id',
        'instructor_id',
        'status',
    ];

    /**
     * Get the department that owns the Course.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the instructor (User) that owns the Course.
     */
    public function instructor()
    {
        // A course belongs to an instructor, which is a User model.
        // We specify 'instructor_id' as the foreign key.
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * Get the quizzes for the course. (Future Relationship)
     */
    // public function quizzes()
    // {
    //     return $this->hasMany(Quiz::class);
    // }

    /**
     * Get the students enrolled in the course. (Future Relationship - Many-to-Many)
     */
    // public function students()
    // {
    //     // This will typically be a many-to-many relationship through an 'enrollments' table
    //     return $this->belongsToMany(User::class, 'enrollments');
    // }
}