<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function myCourses(Request $request)
    {
        $teacher = $request->user();
        
        if ($teacher->role !== 'teacher') {
            abort(403, 'Только для преподавателей');
        }
    
        $courses = Course::with(['users' => function($query) {
            $query->where('course_user.status', '!=', 'rejected');
        }])
        ->where('teacher_id', $teacher->id)
        ->get();
    
        return view('teachers.my-courses', [
            'courses' => $courses,
            'teacher' => $teacher
        ]);
    }
    
    public function leaveCourse(Request $request, $courseId)
    {
        $teacher = $request->user();
        $course = Course::findOrFail($courseId);
    
        // Проверяем, что текущий пользователь действительно учитель этого курса
        if ($course->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized action.');
        }
    
        // Убираем учителя из курса
        $course->teacher_id = null;
        $course->save();
    
        return redirect()->route('teachers.my-courses')->with('success', 'Вы больше не преподаете этот курс');
    }
}