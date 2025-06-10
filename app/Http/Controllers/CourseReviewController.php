<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CourseReview;

class CourseReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $validated['user_id'] = auth()->id();

        CourseReview::create($validated);

        return back()->with('success', 'Спасибо за отзыв!');
    }
}
