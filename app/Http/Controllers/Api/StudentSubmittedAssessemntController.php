<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubmittedAssessment;
use Illuminate\Http\Request;

class StudentSubmittedAssessemntController extends Controller
{
    public function show(SubmittedAssessment $submittedAssessment) {
        return response()->json([
            'submittedAssessment' => $submittedAssessment
        ]);
    }

    public function store(Request $request) {

    }
}
