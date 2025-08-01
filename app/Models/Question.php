<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * These must match your database columns.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'assessment_id',
        'question_text',
        'question_type',
        'points',
        'correct_answer', 
        'order',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'points' => 'integer',
        'order' => 'integer',
    ];
    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }
    public function options()
    {
        return $this->hasMany(QuestionOption::class, 'question_id')->orderBy('option_order');
    }
    public function questionOptions()
    {
        // A Question has many QuestionOptions
        // Assuming 'question_id' is the foreign key in the 'question_options' table
        return $this->hasMany(QuestionOption::class, 'question_id')->orderBy('option_order', 'asc');
    }

    public function submittedQuestions()
    {
        return $this->hasMany(SubmittedQuestion::class);
    }
}

