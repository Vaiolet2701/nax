<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

namespace App\Http\Controllers;

use App\Models\CourseCategory;
use Illuminate\Http\Request;

class CourseCategoryController extends Controller
{
    public function index()
    {
        // Получаем все категории курсов
        $categories = CourseCategory::all();

        // Возвращаем представление с данными
        return view('course_categories.index', compact('categories'));
    }

    public function show($id)
    {
        // Получаем категорию по ID
        $category = CourseCategory::findOrFail($id);
    
        // Получаем курсы этой категории
        $courses = $category->courses;
    
        // Возвращаем представление с данными
        return view('courses.index', compact('category', 'courses')); // Используйте courses.index вместо course_categories.show
    }
    public function showCourses(CourseCategory $category, Request $request)
    {
        // Получаем курсы, связанные с категорией
        $courses = $category->courses;

        // Возвращаем представление с курсами
        return view('courses.index', compact('courses', 'category'));
    }
    
}