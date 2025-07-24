<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmittedAssessment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'assessment_id',
        'score',
        'status',
        'submitted_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'score' => 'integer',
        'submitted_at' => 'datetime',
    ];

    /**
     * Get the student (user) who submitted the assessment.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the original assessment that was submitted.
     */
    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    /**
     * Get the submitted questions for this assessment.
     */
    public function submittedQuestions()
    {
        return $this->hasMany(SubmittedQuestion::class);
    }
}