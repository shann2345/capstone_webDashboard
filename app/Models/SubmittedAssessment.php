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
        'started_at', 
        'completed_at', 
        'submitted_file_path', 
        'original_filename',
        'graded_at',
        'instructor_feedback',
    ];

    protected $casts = [
        'score' => 'integer',
        'submitted_at' => 'datetime',
        'started_at' => 'datetime', 
        'completed_at' => 'datetime',
        'graded_at' => 'datetime',
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
