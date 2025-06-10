<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Promotion;
use App\Models\CourseCategory;
use App\Models\AdminArticle;
use App\Models\Video;
use App\Models\UserArticle;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use Carbon\Carbon;

class PromotionController extends Controller
{


public function index()
{
    $promotions = Promotion::all();
    $categories = CourseCategory::all();
    $reviews = Review::all();

    $courses = Course::where('start_date', '>', Carbon::today())
        ->inRandomOrder()
        ->take(15)
        ->get();


    return view('index', compact(
        'promotions',
        'categories',
        'courses',
        'reviews'
    ));
}
public function loadEnrollForm(Promotion $promotion)
{
    $availableCourses = Course::where('is_active', true)
        ->whereDate('start_date', '>', Carbon::today())
        ->whereDate('end_date', '>', Carbon::today())
        ->get();

    return view('promotions.enroll', [
        'promotion' => $promotion,
        'availableCourses' => $availableCourses
    ]);
}

    
  

public function applyDiscount(Request $request, $promotionId)
{
    // Находим акцию
    $promotion = Promotion::findOrFail($promotionId);

    // Применяем скидку к текущему заказу или курсу
    $course = Course::find($request->input('course_id'));
    $discountedPrice = $course->price * (1 - $promotion->discount / 100);

    // Сохраняем скидку в сессии
    session([
        'applied_discount' => $promotion->discount,
        'discounted_price' => $discountedPrice,
    ]);

    return response()->json([
        'success' => true,
        'discount' => $promotion->discount,
        'discounted_price' => $discountedPrice,
    ]);
}
public function enroll(Request $request, Promotion $promotion)
{
    $data = $request->validate([
        'user_id' => 'required|exists:users,id',
        'promotion_id' => 'required|exists:promotions,id',
        'course_id' => 'required|exists:courses,id',
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
        'age' => 'required|integer|min:12|max:100',
        'attended_previous_courses' => 'required|boolean',
        'message' => 'nullable|string|max:1000',
    ]);

    $user = auth()->user(); // или User::find($data['user_id']);
    $course = Course::findOrFail($data['course_id']);

    // Проверка, что пользователь еще не записан
    if ($user->courses()->where('course_id', $course->id)->exists()) {
        return back()->withErrors(['msg' => 'Вы уже записаны на этот курс']);
    }

    // Подсчет текущего количества участников с нужным статусом (например, 'pending', 'active' и т.д.)
    $currentEnrollmentsCount = $course->users()
        ->wherePivotIn('status', ['pending', 'active', 'in_progress']) // подкорректируйте под ваши статусы
        ->count();

    // Проверяем, есть ли лимит и достигнут ли он
    if ($course->max_people !== null && $currentEnrollmentsCount >= $course->max_people) {
        return back()->withErrors(['msg' => 'Набор на данный курс завершён — достигнут лимит участников']);
    }

    $originalPrice = $course->price;
    $discountedPrice = $originalPrice;

    if ($promotion) {
        $discountedPrice = $originalPrice * (1 - $promotion->discount / 100);
    }

    // Сохраняем данные в pivot таблицу
    $user->courses()->syncWithoutDetaching([
        $course->id => [
            'status' => 'pending',
            'progress' => 0,
            'phone' => $data['phone'],
            'age' => $data['age'],
            'attended_previous_courses' => $data['attended_previous_courses'],
            'message' => $data['message'],
            'original_price' => $originalPrice,
            'discounted_price' => $discountedPrice,
            'completed_at' => null,
            'rejection_reason' => null,
        ]
    ]);

    return back()->with('success', 'Ваша заявка успешно отправлена!');
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