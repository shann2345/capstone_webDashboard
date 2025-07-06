<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'name' => 'required|string|max:255',
        ]);

        $topic = Topic::create([
            'course_id' => $request->course_id,
            'name' => $request->name,
        ]);

        return response()->json($topic);
    }

    public function update(Request $request, Topic $topic)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $topic->update([
            'name' => $request->input('name'),
        ]);

        return response()->json($topic);
    }
}
