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
        'correct_answer', // This column stores the answer based on question_type
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
        return $this->hasMany(QuestionOption::class)->orderBy('option_order');
    }
}

