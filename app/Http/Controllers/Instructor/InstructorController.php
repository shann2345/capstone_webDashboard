<?php

namespace App\Http\Controllers\Instructor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; 

class InstructorController extends Controller
{
    public function index()
    {
        $instructor = Auth::user();
        $courses = $instructor->taughtCourses()->with('program')->get();
        return view('instructor.dashboard', compact('instructor', 'courses'));
    }

}
