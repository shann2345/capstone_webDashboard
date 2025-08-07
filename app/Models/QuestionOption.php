<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * These must match your database columns.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'question_id',
        'option_text',
        'option_order',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'option_order' => 'integer',
    ];
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}

