<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmittedQuestionOption extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'submitted_question_id',
        'question_option_id',
    ];

    /**
     * Get the submitted question that this option belongs to.
     */
    public function submittedQuestion()
    {
        return $this->belongsTo(SubmittedQuestion::class);
    }

    /**
     * Get the original question option associated with this submitted option.
     */
    public function questionOption()
    {
        return $this->belongsTo(QuestionOption::class);
    }
}