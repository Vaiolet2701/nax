<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Review;
use App\Models\UserArticle;
use App\Models\Course;
use App\Models\Trip;
use App\Models\SurvivalTestResult;
use App\Models\CourseReview; // ✅ добавлено

class ProfileController extends Controller
{
    public function show()
    {
        /** @var User $user */
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        $data = ['user' => $user];
        
        Course::checkAndCompleteExpiredCourses();
    
        // Для обычных пользователей
        if ($user->role === 'user') {
            $completedCourses = $user->courses()
                ->wherePivot('status', 'completed')
                ->get();
            
            $rejectedCourses = $user->courses()
                ->wherePivot('status', 'rejected')
                ->get();
            
            // ✅ получаем отзывы пользователя о курсах
            $courseReviews = CourseReview::where('user_id', $user->id)
                ->with('course')
                ->get();

            $data += [
                'reviews' => Review::where('user_id', $user->id)->get(),
                'articles' => UserArticle::where('user_id', $user->id)->get(),
                'completedCourses' => $completedCourses,
                'coursesInProgress' => $user->courses()
                    ->wherePivot('status', 'in_progress')
                    ->get(),
                'rejectedCourses' => $rejectedCourses,
                'survivalTestResults' => SurvivalTestResult::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->limit(3)
                    ->get(),
                'courseReviews' => $courseReviews // ✅ добавлено
            ];
            
            // Обновление уровня
            $completedCoursesCount = $completedCourses->count();
            $newLevel = $this->determineLevel($completedCoursesCount);
            
            if ($user->laravel_level !== $newLevel) {
                $user->update(['laravel_level' => $newLevel]);
                $user->refresh();
            }
        }

        // Для преподавателей
        elseif ($user->role === 'teacher') {
            $data += [
                'taughtCourses' => $user->taughtCourses()->with('users')->get(),
                'activeCourses' => $user->taughtCourses()
                    ->where('end_date', '>', now())
                    ->get()
            ];
        }
        
        return view('profile.show', $data);
    }

    protected function determineLevel(int $count): string
    {
        if ($count >= 8) return 'Продвинутый';
        if ($count >= 5) return 'Средний';
        return 'Начинающий';
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
    
        if (!$user) {
            return redirect()->route('login')->with('error', 'Требуется авторизация');
        }
    
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'age' => $user->canHaveProfileFields() ? 'nullable|integer|min:18|max:100' : '',
            'work_experience' => $user->canHaveProfileFields() ? 'nullable|integer|min:0|max:70' : '',
            'bio' => $user->canHaveProfileFields() ? 'nullable|string|max:1000' : ''
        ]);
    
        $user->update($validated);
    
        return redirect()->route('profile.show')
            ->with('success', 'Профиль успешно обновлен');
    }
}
