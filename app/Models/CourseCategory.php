<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    // Связь с курсами
    public function courses()
    {
        return $this->hasMany(Course::class, 'course_category_id');
    }
}