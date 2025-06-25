<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'question_text',
        'question_type',
        'points',
        'order',
        'correct_answer_text', // For identification/essay
    ];

    // Define the relationship to the Assessment
    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    // Define the hasMany relationship to QuestionOptions
    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }
}