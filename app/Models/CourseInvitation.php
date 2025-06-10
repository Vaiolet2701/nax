<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseInvitation extends Model
{
    protected $fillable = ['course_id', 'teacher_id', 'inviter_id', 'status', 'message'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }
}
