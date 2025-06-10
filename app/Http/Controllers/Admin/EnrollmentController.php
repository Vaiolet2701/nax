<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index()
    {
     $pendingEnrollments = DB::table('course_user')
    ->where('status', 'pending')
    ->join('users', 'course_user.user_id', '=', 'users.id')
    ->join('courses', 'course_user.course_id', '=', 'courses.id')
    ->select(
        'course_user.id as pivot_id',
        'users.name',
        'users.email',
        'courses.title',
        'courses.price',
        'course_user.phone',
        'course_user.age',
        'course_user.message',
        'course_user.status',
        'course_user.created_at',
        'course_user.original_price',
        'course_user.discounted_price'
    )
    ->get();

$allEnrollments = DB::table('course_user')
    ->join('users', 'course_user.user_id', '=', 'users.id')
    ->join('courses', 'course_user.course_id', '=', 'courses.id')
    ->select(
        'course_user.id as pivot_id',
        'users.name',
        'users.email',
        'courses.title',
        'courses.price',
        'course_user.phone',
        'course_user.age',
        'course_user.message',
        'course_user.status',
        'course_user.rejection_reason',
        'course_user.created_at',
        'course_user.original_price',
        'course_user.discounted_price'
    )
    ->orderBy('course_user.created_at', 'desc')
    ->get();

    
        return view('admin.enrollments.index', compact('pendingEnrollments', 'allEnrollments'));
    }


    public function approve(Request $request, $enrollmentId)
{
    $updated = DB::table('course_user')
        ->where('id', $enrollmentId)
        ->update(['status' => 'in_progress']);

    if (!$updated) {
        return response()->json([
            'success' => false,
            'message' => 'Запись не найдена или не обновлена'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'message' => 'Заявка одобрена'
    ]);
}

public function reject(Request $request)
{
    $validated = $request->validate([
        'enrollment_id' => 'required|exists:course_user,id',
        'rejection_reason' => 'required|string|max:500'
    ]);

    $updated = DB::table('course_user')
        ->where('id', $validated['enrollment_id'])
        ->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason']
        ]);

    if (!$updated) {
        return response()->json(['success' => false, 'message' => 'Не удалось отклонить заявку']);
    }

    return response()->json(['success' => true, 'message' => 'Заявка отклонена']);
}
public function rejectForm($enrollmentId)
{
    $enrollment = DB::table('course_user')
        ->where('id', $enrollmentId)
        ->first();
    
    if (!$enrollment) {
        return response()->json(['error' => 'Заявка не найдена'], 404);
    }

    return view('admin.enrollments.reject-form', compact('enrollment'));
}
}