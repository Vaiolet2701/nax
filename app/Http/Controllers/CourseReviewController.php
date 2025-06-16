<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseReviewController extends Controller
{
    public function store(Request $request, Course $course)
    {
        // Проверка аутентификации
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Для оставления отзыва необходимо авторизоваться');
        }

        // Проверка прав
        if (!$course->canUserReview(Auth::user())) {
            return back()
                ->with('error', 'Вы не можете оставить отзыв на этот курс, так как не завершили его');
        }

        // Проверяем, не оставлял ли уже пользователь отзыв
        if ($course->reviews()->where('user_id', Auth::id())->exists()) {
            return back()
                ->with('error', 'Вы уже оставляли отзыв на этот курс');
        }

        // Валидация данных
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10|max:2000',
            'rating' => 'required|integer|between:1,5',
        ]);

        try {
            // Создание отзыва
            $review = new CourseReview($validated);
            $review->user_id = Auth::id();
            $review->course_id = $course->id;
            $review->save();

            return back()
                ->with('success', 'Ваш отзыв успешно сохранен!');
                
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Произошла ошибка при сохранении отзыва: '.$e->getMessage());
        }
    }

    // Дополнительные методы (edit, update, destroy) при необходимости
}