<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmittedQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'submitted_assessment_id',
        'question_id', 
        'question_text', 
        'question_type', 
        'max_points', 
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
    