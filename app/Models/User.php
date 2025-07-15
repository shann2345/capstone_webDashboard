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
        'email_verification_code', // Add this
        'email_verification_code_expires_at', // Add this
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
            'email_verification_code_expires_at' => 'datetime', // Cast this to datetime
        ];
    }

    /**
     * Get the program that the user (e.g., instructor) belongs to.
     */
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Get the courses that the user (e.g., instructor) teaches.
     * This defines a One-to-Many relationship from the instructor's perspective.
     */
    public function taughtCourses()
    {
        // A User (who is an instructor) can teach many Courses.
        // We specify 'instructor_id' as the foreign key in the 'courses' table
        // that points back to this user's 'id'.
        return $this->hasMany(Course::class, 'instructor_id');
    }
}