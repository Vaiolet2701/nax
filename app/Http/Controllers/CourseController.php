<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use Carbon\Carbon;

class CourseController extends Controller
{
public function index(Request $request)
{
    $courses = Course::query();
    
    // Фильтрация по датам
    if ($request->has('start_date') && $request->input('start_date')) {
        $courses->where('start_date', '>=', $request->input('start_date'));
    }
    if ($request->has('end_date') && $request->input('end_date')) {
        $courses->where('end_date', '<=', $request->input('end_date'));
    }
    
    // Фильтрация по количеству людей
    if ($request->has('min_people') && $request->input('min_people')) {
        $courses->where('min_people', '>=', $request->input('min_people'));
    }
    if ($request->has('max_people') && $request->input('max_people')) {
        $courses->where('max_people', '<=', $request->input('max_people'));
    }
    
    // Фильтрация по категории
    if ($request->has('category_id') && $request->input('category_id')) {
        $courses->where('course_category_id', $request->input('category_id'));
    }

    // Фильтрация по цене
    if ($request->has('min_price') && $request->input('min_price')) {
        $courses->where('price', '>=', (float)$request->input('min_price'));
    }
    if ($request->has('max_price') && $request->input('max_price')) {
        $courses->where('price', '<=', (float)$request->input('max_price'));
    }

    // Логика сортировки
    if ($request->sort === 'az') {
        // Сортировка по названию (А–Я)
        $courses->orderBy('title', 'asc');
    } elseif ($request->sort === 'za') {
        // Сортировка по названию (Я–А)
        $courses->orderBy('title', 'desc');
    } elseif ($request->sort === 'newest') {
        // Сортировка по дате начала курса
        $courses->orderBy('start_date', 'desc');
    }

    $courses = $courses->get();
    $categories = CourseCategory::all();

    return view('courses.index', compact('courses', 'categories'));
}

    
public function show($id)
{
    $course = Course::with(['teacher', 'category', 'reviews.user'])->findOrFail($id);

    return view('courses.show', compact('course'));
}

    public function showEnrollForm(Course $course)
    {
        $availableCourses = Course::where('id', '!=', $course->id)
            ->where('start_date', '>', now())
            ->get();
    
        return view('courses.enroll', compact('course', 'availableCourses'));
    }
    
public function enroll(Request $request, Course $course)
{
    $user = Auth::user();

    $validated = $request->validate([
        'course_id' => 'required|exists:courses,id',
        'phone' => 'required|string|max:20',
        'age' => 'required|integer|min:12|max:100',
        'attended_previous_courses' => 'required|boolean',
        'message' => 'nullable|string|max:1000',
    ]);

    // Проверяем, не записан ли уже пользователь
    if ($user->courses()->where('course_id', $validated['course_id'])->exists()) {
        return redirect()->back()->with('error', 'Вы уже записаны на этот курс');
    }

    // Считаем количество подтвержденных участников
    $currentEnrollmentsCount = DB::table('course_user')
        ->where('course_id', $validated['course_id'])
        ->whereIn('status', ['in_progress', 'completed'])
        ->count();

    // Получаем максимальное количество участников курса
    $maxPeople = $course->max_people;

    if ($maxPeople !== null && $currentEnrollmentsCount >= $maxPeople) {
        return redirect()->back()->with('error', 'Набор на данный курс завершён — достигнут лимит участников');
    }

    // Создаем запись
    $user->courses()->attach($validated['course_id'], [
        'status' => 'pending',
        'phone' => $validated['phone'],
        'age' => $validated['age'],
        'attended_previous_courses' => $validated['attended_previous_courses'],
        'message' => $validated['message'] ?? null,
    ]);

    return redirect()->back()->with('success', 'Вы успешно записаны на курс! Ожидайте подтверждения.');
}

public function repeatCourse(Course $course)
{
    if (!$course->is_repeated) {
        return response()->json(['error' => 'Этот курс не является повторяющимся'], 400);
    }

    $newCourse = $course->replicate();
    $newCourse->id = null;

    if ($course->start_date) {
        $startDate = Carbon::parse($course->start_date)->copy()->addMonth();
        $newCourse->start_date = $startDate->format('Y-m-d');
    }

    if ($course->end_date) {
        $endDate = Carbon::parse($course->end_date)->copy()->addMonth();
        $newCourse->end_date = $endDate->format('Y-m-d');
    }

    $newCourse->parent_course_id = $course->id;
    $newCourse->save();

    return response()->json(['success' => 'Повтор курса успешно создан']);
}

    public function showMap()
{
    $courses = Course::whereNotNull('latitude')
                     ->whereNotNull('longitude')
                     ->where('is_active', true)
                     ->get();

    return view('courses.map', compact('courses'));
}

    }
    