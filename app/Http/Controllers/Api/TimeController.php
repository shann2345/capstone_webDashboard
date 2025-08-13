<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TimeController extends Controller
{
    public function index()
    {
        return response()->json([
            'server_time' => Carbon::now()->toIso8601String(),
        ]);
    }
}