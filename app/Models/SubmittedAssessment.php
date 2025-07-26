<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmittedAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'assessment_id',
        'score',
        'status',
        'submitted_at',
        'started_at', // Added for tracking quiz start time
        'completed_at', // Added for tracking quiz completion time
        'submitted_file_path', // Added for assignment submissions
    ];

    protected $casts = [
        'score' => 'integer',
        'submitted_at' => 'datetime',
        'started_at' => 'datetime', // Cast to datetime
        'completed_at' => 'datetime', // Cast to datetime
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function submittedQuestions()
    {
        return $this->hasMany(SubmittedQuestion::class);
    }
}
