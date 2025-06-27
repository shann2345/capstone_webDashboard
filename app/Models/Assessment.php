<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    // By default, Laravel will assume the table name is 'assessments' (plural),
    // which matches our migration, so no need for protected $table = 'assessments';

    protected $fillable = [
        'course_id',
        'material_id',
        'title',
        'description',
        'encrypted_file_path', // <-- ADDED: For uploaded quiz/exam files
        'original_filename',   // <-- ADDED: For uploaded quiz/exam files
        'type',
        'available_at',
        'unavailable_at',
        'duration_minutes',
        'access_code',
    ];

    /**
     * The attributes that should be cast.
     * These convert database fields into Carbon instances for date/time.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'available_at' => 'datetime',   // <-- ADDED: Cast to Carbon instance
        'unavailable_at' => 'datetime', // <-- ADDED: Cast to Carbon instance
    ];

    /**
     * Get the course that owns the assessment.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function material()
    {
        return $this->belongsTo(Material::class);
    }
     public function questions()
    {
        return $this->hasMany(Question::class);
    }
    /**
     * Helper to check if the assessment is currently available based on its schedule.
     * Takes into account nullable available_at/unavailable_at timestamps.
     */
    public function isAvailable(): bool
    {
        $now = now();
        return ($this->available_at === null || $now->greaterThanOrEqualTo($this->available_at)) &&
               ($this->unavailable_at === null || $now->lessThanOrEqualTo($this->unavailable_at));
    }
}