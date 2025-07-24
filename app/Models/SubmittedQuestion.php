<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmittedQuestion extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'submitted_assessment_id',
        'question_id',
        'submitted_answer',
        'is_correct',
        'score_earned',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_correct' => 'boolean',
        'score_earned' => 'integer',
    ];

    /**
     * Get the submitted assessment that owns the submitted question.
     */
    public function submittedAssessment()
    {
        return $this->belongsTo(SubmittedAssessment::class);
    }

    /**
     * Get the original question associated with this submitted question.
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get the submitted options for the submitted question (for multiple-choice).
     */
    public function submittedOptions()
    {
        return $this->hasMany(SubmittedQuestionOption::class);
    }
}