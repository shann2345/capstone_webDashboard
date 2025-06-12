<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Auth facade (optional, for displaying user info)

class InstructorController extends Controller
{
    public function index()
    {
        return view('instructor.dashboard'); // Loads resources/views/instructor/dashboard.blade.php
    }
}
