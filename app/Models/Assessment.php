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
        $now = now(); // Gets the current Carbon instance (server time)

        // Check if available_at is null OR available_at is less than or equal to now
        $isAvailableFrom = is_null($this->available_at) || $this->available_at->lte($now);

        // Check if unavailable_at is null OR unavailable_at is greater than or equal to now
        $isAvailableUntil = is_null($this->unavailable_at) || $this->unavailable_at->gte($now);

        // Material is available if both conditions are true
        return $isAvailableFrom && $isAvailableUntil;
    }
}