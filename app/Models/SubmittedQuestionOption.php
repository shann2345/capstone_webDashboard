<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmittedQuestionOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'submitted_question_id',
        'question_option_id', // Reference to original option
        'option_text', // Added: Snapshot of the option text
        'is_correct_option', // Added: Was this option correct at the time of snapshot?
        'is_selected', // Added: Did the student select this option?
    ];

    protected $casts = [
        'is_correct_option' => 'boolean', // Added
        'is_selected' => 'boolean', // Added
    ];

    public function submittedQuestion()
    {
        return $this->belongsTo(SubmittedQuestion::class);
    }
    public function questionOption()
    {
        return $this->belongsTo(QuestionOption::class, 'question_option_id');
    }
}
    