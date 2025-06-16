<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // Добавьте этот импорт
use Illuminate\Support\Carbon;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'course_category_id', 
        'start_date', 'end_date', 'image_path',
        'min_people', 'max_people', 'teacher_id', 'is_repeated', 
        'animals', 'price', 'is_active', 'latitude', 'longitude', 'location_name'
    ];

    public function category()
    {
        return $this->belongsTo(CourseCategory::class, 'course_category_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

public function users()
{
    return $this->belongsToMany(User::class)
                ->withPivot([
                    'status',
                    'progress',
                    'phone',
                    'age',
                    'attended_previous_courses',
                    'message',
                    'rejection_reason',
                    'completed_at',
                    'original_price',
                    'discounted_price',
                ])
                ->withTimestamps();
}




public static function checkAndCompleteExpiredCourses()
{
    $courses = self::where('end_date', '<', now())
        ->whereHas('users', function($q) {
            $q->where('status', 'in_progress');
        })
        ->with(['users' => function($q) {
            $q->where('status', 'in_progress');
        }])
        ->get();

    foreach ($courses as $course) {
        $course->users()->updateExistingPivot(
            $course->users->pluck('id'),
            ['status' => 'completed', 'completed_at' => now()]
        );
    }
}
    // Все преподаватели курса (через промежуточную таблицу)
    public function teachers()
    {
        return $this->belongsToMany(User::class, 'course_teacher', 'course_id', 'teacher_id')
            ->withPivot('is_active')
            ->withTimestamps();
    }
    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }
public function courseReviews()
{
    return $this->hasMany(CourseReview::class);
}
  public function getIsCompletedAttribute(): bool
    {
        return $this->end_date && Carbon::parse($this->end_date)->isPast();
    }

    // Проверка, есть ли похожие (повторяющиеся) курсы в будущем
    public function hasFutureRepeat(): bool
    {
        return self::where('title', $this->title)
            ->where('id', '!=', $this->id)
            ->where('start_date', '>', now())
            ->exists();
    }
    public function parentCourse()
{
    return $this->belongsTo(Course::class, 'parent_course_id');
}

public function repeatedCourses()
{
    return $this->hasMany(Course::class, 'parent_course_id');
}
public function getHasFutureRepeatAttribute()
{
    return self::where('parent_course_id', $this->id)
        ->where('start_date', '>', now())
        ->exists();
}

public function reviews()
{
    // Получаем все отзывы для этого курса
    $reviews = $this->hasMany(CourseReview::class);
    
    // Если есть родительский курс, добавляем его отзывы
    if ($this->parent_course_id) {
        $parentReviews = CourseReview::where('original_course_id', $this->parent_course_id)
            ->orWhere('course_id', $this->parent_course_id);
            
        return CourseReview::where('course_id', $this->id)
            ->union($parentReviews);
    }
    
    // Если это родительский курс, добавляем отзывы всех дочерних курсов
    if ($this->repeatedCourses()->exists()) {
        $childReviews = CourseReview::whereIn('course_id', 
            $this->repeatedCourses()->pluck('id'))
            ->whereNotNull('original_course_id')
            ->where('original_course_id', $this->id);
            
        return $reviews->union($childReviews);
    }
    
    return $reviews;
}

public function canUserReview(User $user)
{
    // Проверяем, что курс завершен
    if (!$this->is_completed) {
        return false;
    }

    // Проверяем, что пользователь завершил этот курс
    return $this->users()
        ->where('user_id', $user->id)
        ->where('status', 'completed')
        ->exists();
}
}