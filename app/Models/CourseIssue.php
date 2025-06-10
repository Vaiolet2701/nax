<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseIssue extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'course_id',
        'user_id',
        'type',
        'description',
    ];
}
