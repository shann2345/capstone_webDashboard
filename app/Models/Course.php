<?php

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
        'program_id',
        'instructor_id',
        'status',
    ];

    /**
     * Get the department that owns the Course.
     * This defines a Many-to-One relationship.
     */
    public function program()
    {
        return $this->belongsTo(Program::class); // A Course belongs to one Department
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
    public function instructor()
    {
        // A Course belongs to one Instructor (who is a User model).
        // We specify 'instructor_id' as the foreign key in the 'courses' table.
        return $this->belongsTo(User::class, 'instructor_id');
    }
    public function materials()
    {
        return $this->hasMany(Material::class);
    }
    public function assessments() {
        return $this->hasMany(Assessment::class);
    }

    // Add future relationships here, e.g., to Quizzes, Students, etc.
    // public function quizzes() { return $this->hasMany(Quiz::class); }
    // public function students() { return $this->belongsToMany(User::class, 'enrollments'); }
    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'course_id', 'student_id')
                    ->withPivot('status', 'enrollment_date', 'grade') // Include pivot table columns
                    ->withTimestamps(); // If your pivot table has created_at/updated_at
    }
}