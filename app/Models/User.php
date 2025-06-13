<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department_id', 
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- Start of New Relationships for User Model ---

    /**
     * Get the department that the user (e.g., instructor) belongs to.
     * This defines a Many-to-One relationship.
     */
    public function department()
    {
        // A User belongs to one Department.
        // Laravel will look for 'department_id' on the 'users' table by default.
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the courses that the user (e.g., instructor) teaches.
     * This defines a One-to-Many relationship (from the instructor's perspective).
     */
    public function taughtCourses()
    {
        // A User (who is an instructor) can teach many Courses.
        // We specify 'instructor_id' as the foreign key in the 'courses' table
        // that points back to this user's 'id'.
        return $this->hasMany(Course::class, 'instructor_id');
    }

    // --- End of New Relationships for User Model ---
}