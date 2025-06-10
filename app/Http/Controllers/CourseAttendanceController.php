<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CourseIssue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseAttendanceController extends Controller
{
public function showAttendancePage($courseUserId)
{
    // Получаем запись из таблицы course_user
    $courseUser = DB::table('course_user')->where('id', $courseUserId)->first();

    if (!$courseUser) {
        abort(404, 'Запись о посещаемости не найдена');
    }

    return view('teachers.attendance', compact('courseUser'));
}

public function updateAttendance(Request $request, $courseUserId)
{
    $request->validate([
        'attended' => 'required|boolean',
        'checked_in_at' => 'nullable|date',
        'teacher_comment' => 'nullable|string|max:1000',
    ]);

    DB::table('course_user')
        ->where('id', $courseUserId)
        ->update([
            'attended' => $request->attended,
            'checked_in_at' => $request->checked_in_at,
            'teacher_comment' => $request->teacher_comment,
            'updated_at' => now(),
        ]);

    return redirect()->back()->with('success', 'Информация обновлена.');
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
