<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseReview extends Model
{
    protected $table = 'course_reviews';

    protected $fillable = [
        'course_id',
        'user_id',
        'title',
        'content',
        'rating',
    ];

    // Связь с курсом
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Связь с пользователем
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
