<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Auth facade (optional, for displaying user info)

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard'); // Loads resources/views/admin/dashboard.blade.php
    }
}