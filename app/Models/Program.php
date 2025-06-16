<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', // Only 'name' can be mass-assigned for now
    ];

    public function users()
    {
        return $this->hasMany(User::class); // Laravel will look for 'department_id' on the 'users' table by default.
    }

    public function courses()
    {
        return $this->hasMany(Course::class); // Laravel will look for 'department_id' on the 'courses' table by default.
    }
}