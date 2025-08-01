<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'program_id',
        'email_verification_code',
        'email_verification_code_expires_at',
        'google_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'email_verification_code_expires_at' => 'datetime',
        ];
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function taughtCourses()
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'enrollments', 'student_id', 'course_id')
                    ->withPivot('status', 'enrollment_date', 'grade')
                    ->withTimestamps();
    }

    /**
     * Check if the user is enrolled in a specific course.
     * @param int $courseId
     * @return bool
     */
    public function isEnrolledInCourse(int $courseId): bool
    {
        return $this->courses()->where('course_id', $courseId)->exists();
    }
}