<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'name', 
        'course_id',
        'user_id',
    ];

    // Relationship with Course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    // Relationship with Students
    public function students()
    {
        return $this->hasMany(User::class, 'section_id'); // Assuming 'section_id' is in the users table
    }
}
