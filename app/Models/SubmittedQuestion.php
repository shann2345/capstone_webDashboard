<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmittedQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'submitted_assessment_id',
        'question_id', // Reference to original question
        'question_text', // Added: Snapshot of the question text
        'question_type', // Added: Snapshot of the question type
        'max_points', // Added: Snapshot of the max points for this question
        'submitted_answer',
        'is_correct',
        'score_earned',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'score_earned' => 'integer',
    ];

    public function submittedAssessment()
    {
        return $this->belongsTo(SubmittedAssessment::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function submittedOptions()
    {
        return $this->hasMany(SubmittedQuestionOption::class);
    }
}
    