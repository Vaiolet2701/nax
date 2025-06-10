<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'name',
        'email',
        'promotion_id',
        'phone',
        'age',
        'attended_previous_courses',
        'message',
        'status',
        'price',
    ];

    // Отношение к пользователю
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Отношение к курсу
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }
}