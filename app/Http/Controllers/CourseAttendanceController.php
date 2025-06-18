<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CourseIssue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Course;

class CourseAttendanceController extends Controller
{
public function showAttendancePage($userId)
{
    $user = User::findOrFail($userId);
    $course = auth()->user()->courses()->firstOrFail();
    
    $courseUser = DB::table('course_user')
        ->where('user_id', $user->id)
        ->where('course_id', $course->id)
        ->firstOrFail();

    return view('teachers.attendance', compact('courseUser', 'course', 'user'));
}

public function updateAttendance(Request $request, User $user)
{
    $request->validate([
        'attended' => 'required|boolean',
        'course_id' => 'required|exists:courses,id'
    ]);

    $teacher = auth()->user();
    $course = $teacher->courses()->findOrFail($request->course_id);

    DB::table('course_user')
        ->where('user_id', $user->id)
        ->where('course_id', $course->id)
        ->update([
            'attended' => $request->attended,
            'checked_in_at' => $request->attended ? now() : null,
            'updated_at' => now()
        ]);

    return back()->with('success', 'Посещаемость обновлена');
}
public function quickUpdate(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'course_id' => 'required|exists:courses,id',
        'attended' => 'required|boolean'
    ]);

    $teacher = auth()->user();
    $course = Course::findOrFail($request->course_id);
    
    if ($course->teacher_id != $teacher->id) {
        abort(403, 'Это не ваш курс');
    }

    // Обновляем через отношение, чтобы обновить кеш отношений
    $teacher->taughtCourses()
        ->where('courses.id', $course->id)
        ->first()
        ->users()
        ->updateExistingPivot($request->user_id, [
            'attended' => (bool)$request->attended,
            'checked_in_at' => $request->attended ? now() : null
        ]);

    // Очищаем кеш отношений
    Cache::forget("course_{$request->course_id}_users");
    Cache::forget("user_{$request->user_id}_courses");

    return back()->with('success', 'Посещаемость обновлена');
}
    public function submitIssue(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'user_id' => 'nullable|exists:users,id',
            'type' => 'required|in:discipline,absence,other',
            'description' => 'required|string|max:2000',
        ]);

        CourseIssue::create([
            'teacher_id' => auth()->id(),
            'course_id' => $request->course_id,
            'user_id' => $request->user_id,
            'type' => $request->type,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Жалоба отправлена администратору.');
    }
}
