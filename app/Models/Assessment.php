<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * These must match your database columns.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'course_id',
        'title',
        'type',
        'description',
        'assessment_file_path',
        'duration_minutes',
        'access_code',
        'available_at',
        'unavailable_at',
        'created_by',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'available_at' => 'datetime',
        'unavailable_at' => 'datetime',
    ];
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by'); 
    }
    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }
    public function topic() 
    {
        return $this->belongsTo(Topic::class);
    }
}

